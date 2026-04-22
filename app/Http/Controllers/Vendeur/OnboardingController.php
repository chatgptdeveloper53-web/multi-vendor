<?php

namespace App\Http\Controllers\Vendeur;

use App\Enums\TypeDocument;
use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Logistique;
use App\Services\ViesService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class OnboardingController extends Controller
{
    public function __construct(private ViesService $vies) {}

    // ─────────────────────────────────────────────────────────────
    //  Dispatcher : affiche l'étape courante
    // ─────────────────────────────────────────────────────────────

    public function show(int $etape = 1): View|RedirectResponse
    {
        $vendeur = Auth::user()->vendeur;

        if (!$vendeur) {
            return redirect()->route('home');
        }

        // Profil déjà complet → page d'attente
        if ($vendeur->onboardingComplet()) {
            return redirect()->route('vendeur.pending');
        }

        // Ne pas permettre de sauter des étapes
        if ($etape > ($vendeur->etape_onboarding ?? 1)) {
            return redirect()->route('vendeur.onboarding.etape', $vendeur->etape_onboarding ?? 1);
        }

        if ($etape < 1 || $etape > 5) {
            return redirect()->route('vendeur.onboarding.etape', 1);
        }

        // Charger les documents pour l'étape 2, 3 et 5
        if (in_array($etape, [2, 3, 5])) {
            $vendeur->load('documents');
        }
        if (in_array($etape, [4, 5])) {
            $vendeur->load('logistiques');
        }

        return view("vendeur.onboarding.etape{$etape}", compact('vendeur'));
    }

    // ─────────────────────────────────────────────────────────────
    //  Étape 1 — Société (merged: identité + représentant légal)
    // ─────────────────────────────────────────────────────────────

    public function saveEtape1(Request $request): RedirectResponse
    {
        $request->validate([
            // Société
            'raison_sociale'   => ['required', 'string', 'max:255'],
            'forme_juridique'  => ['required', 'string', 'max:100'],
            'siret'            => ['required', 'string', 'size:14', 'regex:/^\d{14}$/'],
            'numero_tva'       => ['required', 'string', 'max:20'],
            'pays'             => ['required', 'string', 'size:2'],
            'telephone'        => ['required', 'string', 'max:20'],
            'site_web'         => ['nullable', 'url', 'max:255'],
            'adresse_siege'    => ['required', 'string', 'max:500'],
            // Représentant légal
            'nom_dirigeant'    => ['required', 'string', 'max:255'],
            'fonction_dirigeant' => ['required', 'string', 'max:100'],
            'email_commercial' => ['nullable', 'email', 'max:255'],
        ], [
            'siret.size'       => 'Le SIRET doit contenir exactement 14 chiffres.',
            'siret.regex'      => 'Le SIRET ne doit contenir que des chiffres.',
            'adresse_siege.required' => "L'adresse du siège social est obligatoire.",
        ]);

        $vendeur = Auth::user()->vendeur;

        // Vérification VIES non bloquante (on enregistre le statut)
        $viesResult = $this->vies->validate($request->numero_tva);

        $vendeur->update([
            // Société
            'raison_sociale'   => $request->raison_sociale,
            'forme_juridique'  => $request->forme_juridique,
            'siret'            => $request->siret,
            'numero_tva'       => strtoupper(trim($request->numero_tva)),
            'tva_verifiee'     => $viesResult['valid'],
            'pays'             => strtoupper($request->pays),
            'telephone'        => $request->telephone,
            'site_web'         => $request->site_web,
            'adresse_siege'    => $request->adresse_siege,
            // Représentant légal
            'nom_dirigeant'    => $request->nom_dirigeant,
            'fonction_dirigeant' => $request->fonction_dirigeant,
            'email_commercial' => $request->email_commercial,
            // Progression
            'etape_onboarding' => max($vendeur->etape_onboarding, 2),
        ]);

        $msg = $viesResult['valid']
            ? '✅ TVA vérifiée via VIES — Entreprise : ' . ($viesResult['name'] ?? $request->raison_sociale)
            : '⚠️ TVA non confirmée par VIES (le dossier sera examiné manuellement).';

        return redirect()->route('vendeur.onboarding.etape', 2)->with('vies_info', $msg);
    }

    // ─────────────────────────────────────────────────────────────
    //  Étape 2 — Documents réglementaires (4 obligatoires)
    // ─────────────────────────────────────────────────────────────

    public function saveEtape2(Request $request): RedirectResponse
    {
        $vendeur = Auth::user()->vendeur;

        // Vérifier les documents existants
        $hasKbis = $vendeur->documents()->where('type', TypeDocument::KBIS)->exists();
        $hasStatuts = $vendeur->documents()->where('type', TypeDocument::STATUTS_SOCIETE)->exists();
        $hasPieceIdentite = $vendeur->documents()->where('type', TypeDocument::PIECE_IDENTITE_DIRIGEANT)->exists();
        $hasRib = $vendeur->documents()->where('type', TypeDocument::RIB_BANCAIRE)->exists();

        $request->validate([
            'kbis'                        => [$hasKbis ? 'nullable' : 'required', 'file', 'mimes:pdf', 'max:5120'],
            'statuts_societe'             => [$hasStatuts ? 'nullable' : 'required', 'file', 'mimes:pdf', 'max:5120'],
            'piece_identite_dirigeant'    => [$hasPieceIdentite ? 'nullable' : 'required', 'file', 'mimes:pdf', 'max:5120'],
            'rib_bancaire'                => [$hasRib ? 'nullable' : 'required', 'file', 'mimes:pdf', 'max:5120'],
        ], [
            'kbis.required'                   => 'Le K-Bis est obligatoire.',
            'statuts_societe.required'        => 'Les statuts de la société sont obligatoires.',
            'piece_identite_dirigeant.required' => "La pièce d'identité du dirigeant est obligatoire.",
            'rib_bancaire.required'           => 'Le RIB bancaire est obligatoire.',
        ]);

        // Helper pour stocker un fichier et créer/remplacer un Document
        $store = function ($file, TypeDocument $type) use ($vendeur) {
            $path = $file->store("documents/{$vendeur->id}", 'public');
            $original = $file->getClientOriginalName();
            Document::create([
                'vendeur_id'   => $vendeur->id,
                'type'         => $type,
                'fichier'      => $path,
                'nom_original' => $original,
                'valide'       => false,
            ]);
        };

        // Traiter les 4 documents obligatoires (remplacer l'ancien si existant)
        $requiredDocs = [
            'kbis'                     => TypeDocument::KBIS,
            'statuts_societe'          => TypeDocument::STATUTS_SOCIETE,
            'piece_identite_dirigeant' => TypeDocument::PIECE_IDENTITE_DIRIGEANT,
            'rib_bancaire'             => TypeDocument::RIB_BANCAIRE,
        ];

        foreach ($requiredDocs as $field => $type) {
            if ($request->hasFile($field)) {
                // Supprimer l'ancien document si existant
                $vendeur->documents()->where('type', $type)->get()->each(function ($doc) {
                    Storage::disk('public')->delete($doc->fichier);
                    $doc->delete();
                });
                $store($request->file($field), $type);
            }
        }

        $vendeur->update(['etape_onboarding' => max($vendeur->etape_onboarding, 3)]);

        return redirect()->route('vendeur.onboarding.etape', 3)
            ->with('success', 'Documents enregistrés avec succès.');
    }

    // ─────────────────────────────────────────────────────────────
    //  Étape 3 — Certifications EnR (optionnelle)
    // ─────────────────────────────────────────────────────────────

    public function saveEtape3(Request $request): RedirectResponse
    {
        $request->validate([
            'certificats_ce'           => ['nullable', 'array', 'max:20'],
            'certificats_ce.*'         => ['file', 'mimes:pdf', 'max:10240'],
            'categorie_ce'             => ['nullable', 'string'],
            'fiches_ppe2'              => ['nullable', 'array', 'max:20'],
            'fiches_ppe2.*'            => ['file', 'mimes:pdf', 'max:10240'],
            'categorie_ppe2'           => ['nullable', 'string'],
        ]);

        $vendeur = Auth::user()->vendeur;

        // Helper pour stocker un fichier de certification
        $store = function ($file, TypeDocument $type, ?string $categorie) use ($vendeur) {
            $path = $file->store("documents/{$vendeur->id}", 'public');
            $original = $file->getClientOriginalName();
            Document::create([
                'vendeur_id'   => $vendeur->id,
                'type'         => $type,
                'fichier'      => $path,
                'nom_original' => $original,
                'categorie'    => $categorie,
                'valide'       => false,
            ]);
        };

        // Certificats CE
        if ($request->hasFile('certificats_ce')) {
            foreach ($request->file('certificats_ce') as $file) {
                $store($file, TypeDocument::CERTIFICAT_CE, $request->categorie_ce);
            }
        }

        // Fiches PPE2
        if ($request->hasFile('fiches_ppe2')) {
            foreach ($request->file('fiches_ppe2') as $file) {
                $store($file, TypeDocument::PPE2, $request->categorie_ppe2);
            }
        }

        $vendeur->update(['etape_onboarding' => max($vendeur->etape_onboarding, 4)]);

        return redirect()->route('vendeur.onboarding.etape', 4)
            ->with('success', 'Certifications enregistrées avec succès.');
    }

    // ─────────────────────────────────────────────────────────────
    //  Étape 4 — Logistique & Transport
    // ─────────────────────────────────────────────────────────────

    public function saveEtape4(Request $request): RedirectResponse
    {
        $request->validate([
            'adresse_expedition'     => ['required', 'string', 'max:500'],
            'poids_max_palette'      => ['required', 'numeric', 'min:1'],
            'incoterm_preference'    => ['required', 'string', 'in:DDP,EXW,DAP,FCA,LIBRE'],
            'incoterm_notes'         => ['nullable', 'string', 'max:1000'],
            'moq'                    => ['nullable', 'integer', 'min:1'],
            'delai_traitement_jours' => ['nullable', 'integer', 'min:1', 'max:90'],
            'politique_retour'       => ['nullable', 'string', 'max:2000'],
            'matrice_transport'      => ['nullable', 'file', 'mimes:csv,txt,xlsx', 'max:2048'],
            'lignes'                 => ['nullable', 'array'],
            'lignes.*.zone'          => ['required_with:lignes', 'string', 'max:100'],
            'lignes.*.poids_min'     => ['nullable', 'numeric', 'min:0'],
            'lignes.*.poids'         => ['nullable', 'numeric', 'min:0'],
            'lignes.*.prix'          => ['nullable', 'numeric', 'min:0'],
            'lignes.*.tarif_par_kg'  => ['nullable', 'numeric', 'min:0'],
            'lignes.*.delai_jours'   => ['nullable', 'integer', 'min:1'],
            'lignes.*.incoterm'      => ['nullable', 'string'],
        ]);

        $vendeur = Auth::user()->vendeur;

        // Enregistrer les préférences logistiques
        $updates = [
            'adresse_expedition'     => $request->adresse_expedition,
            'poids_max_palette'      => (float) $request->poids_max_palette,
            'incoterm_preference'    => $request->incoterm_preference,
            'incoterm_notes'         => $request->incoterm_notes,
            'moq'                    => $request->moq,
            'delai_traitement_jours' => $request->delai_traitement_jours,
            'politique_retour'       => $request->politique_retour,
            'etape_onboarding'       => max($vendeur->etape_onboarding, 5),
        ];

        // Import CSV de la matrice de transport
        if ($request->hasFile('matrice_transport')) {
            $file = $request->file('matrice_transport');
            $path = $file->store("matrices/{$vendeur->id}", 'public');
            $updates['matrice_transport_fichier'] = $path;

            // Parser le CSV → créer les lignes logistiques
            $this->importMatriceCSV($vendeur->id, storage_path("app/public/{$path}"));
        }

        // Lignes manuelles
        if ($request->filled('lignes')) {
            $vendeur->logistiques()->where('source', 'manual')->delete();

            foreach ($request->lignes as $ligne) {
                if (empty($ligne['zone'])) continue;
                Logistique::create([
                    'vendeur_id'   => $vendeur->id,
                    'zone'         => $ligne['zone'],
                    'poids_min'    => $ligne['poids_min'] ?? 0,
                    'poids'        => $ligne['poids'] ?? 0,
                    'prix'         => $ligne['prix'] ?? 0,
                    'tarif_par_kg' => $ligne['tarif_par_kg'] ?? null,
                    'delai_jours'  => $ligne['delai_jours'] ?? null,
                    'incoterm'     => $ligne['incoterm'] ?? $request->incoterm_preference,
                    'source'       => 'manual',
                ]);
            }
        }

        $vendeur->update($updates);

        return redirect()->route('vendeur.onboarding.etape', 5);
    }

    // ─────────────────────────────────────────────────────────────
    //  Étape 5 — Récapitulatif & Confirmation
    // ─────────────────────────────────────────────────────────────

    public function saveEtape5(Request $request): RedirectResponse
    {
        $request->validate([
            'informations_legales' => ['nullable', 'string', 'max:3000'],
            'consent_final'        => ['accepted'],
        ], [
            'consent_final.accepted' => 'Vous devez accepter les conditions avant de soumettre.',
        ]);

        $vendeur = Auth::user()->vendeur;

        $vendeur->update([
            'informations_legales' => $request->informations_legales,
            'profil_complet'       => true,
            'etape_onboarding'     => 6, // > 5 = terminé
        ]);

        return redirect()->route('vendeur.pending');
    }

    // ─────────────────────────────────────────────────────────────
    //  AJAX — Vérification TVA VIES
    // ─────────────────────────────────────────────────────────────

    public function checkVies(Request $request)
    {
        $request->validate(['tva' => ['required', 'string', 'max:20']]);
        return response()->json($this->vies->validate($request->tva));
    }

    // ─────────────────────────────────────────────────────────────
    //  Pages statiques vendeur
    // ─────────────────────────────────────────────────────────────

    public function pending(): View
    {
        $vendeur = Auth::user()->vendeur()->with('documents', 'logistiques')->firstOrFail();
        return view('vendeur.onboarding.pending', compact('vendeur'));
    }

    public function rejected(): View
    {
        $vendeur = Auth::user()->vendeur()->with('documents')->firstOrFail();
        return view('vendeur.onboarding.rejected', compact('vendeur'));
    }

    // ─────────────────────────────────────────────────────────────
    //  Suppression d'un document (AJAX)
    // ─────────────────────────────────────────────────────────────

    public function deleteDocument(int $documentId)
    {
        $vendeur = Auth::user()->vendeur;
        $doc = Document::where('vendeur_id', $vendeur->id)->findOrFail($documentId);
        Storage::disk('public')->delete($doc->fichier);
        $doc->delete();
        return response()->json(['ok' => true]);
    }

    // ─────────────────────────────────────────────────────────────
    //  Helpers privés
    // ─────────────────────────────────────────────────────────────

    private function importMatriceCSV(int $vendeurId, string $filePath): void
    {
        // Supprimer les lignes précédemment importées
        Logistique::where('vendeur_id', $vendeurId)->where('source', 'import')->delete();

        if (!file_exists($filePath)) return;

        $handle = fopen($filePath, 'r');
        if (!$handle) return;

        $header  = null;
        $created = 0;

        while (($row = fgetcsv($handle, 1000, ',')) !== false) {
            // Auto-detect separator (comma ou semicolon)
            if (count($row) === 1) {
                $row = str_getcsv($row[0], ';');
            }

            // Première ligne = en-têtes
            if ($header === null) {
                $header = array_map('strtolower', array_map('trim', $row));
                continue;
            }

            // Map colonnes par nom (tolérant aux variations)
            // Pad or slice row to match header length
            $padded_row = array_pad($row, count($header), null);
            $padded_row = array_slice($padded_row, 0, count($header));
            $data = array_combine($header, $padded_row);

            $zone = trim($data['zone'] ?? $data['zone_livraison'] ?? '');
            if (empty($zone)) continue;

            Logistique::create([
                'vendeur_id'   => $vendeurId,
                'zone'         => $zone,
                'poids_min'    => (float) ($data['poids_min_kg'] ?? $data['poids_min'] ?? 0),
                'poids'        => (float) ($data['poids_max_kg'] ?? $data['poids_max'] ?? $data['poids'] ?? 0),
                'prix'         => (float) ($data['prix_base_eur'] ?? $data['prix'] ?? 0),
                'tarif_par_kg' => (float) ($data['prix_par_kg_eur'] ?? $data['tarif_par_kg'] ?? 0) ?: null,
                'delai_jours'  => (int)   ($data['delai_jours'] ?? 0) ?: null,
                'incoterm'     => strtoupper(trim($data['incoterm'] ?? '')),
                'description'  => trim($data['description'] ?? ''),
                'source'       => 'import',
            ]);

            $created++;
            if ($created >= 500) break; // sécurité
        }

        fclose($handle);
    }
}
