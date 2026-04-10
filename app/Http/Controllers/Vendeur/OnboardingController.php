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

        // Ne pas permettre de sauter des étapes
        if ($etape > ($vendeur->etape_onboarding ?? 1)) {
            return redirect()->route('vendeur.onboarding.etape', $vendeur->etape_onboarding ?? 1);
        }

        if ($etape < 1 || $etape > 5) {
            return redirect()->route('vendeur.onboarding.etape', 1);
        }

        return view("vendeur.onboarding.etape{$etape}", compact('vendeur'));
    }

    // ─────────────────────────────────────────────────────────────
    //  Étape 1 — Identité de l'entreprise
    // ─────────────────────────────────────────────────────────────

    public function saveEtape1(Request $request): RedirectResponse
    {
        $request->validate([
            'raison_sociale'   => ['required', 'string', 'max:255'],
            'forme_juridique'  => ['required', 'string', 'max:100'],
            'siret'            => ['required', 'string', 'size:14', 'regex:/^\d{14}$/'],
            'numero_tva'       => ['required', 'string', 'max:20'],
            'pays'             => ['required', 'string', 'size:2'],
            'telephone'        => ['required', 'string', 'max:20'],
            'site_web'         => ['nullable', 'url', 'max:255'],
        ], [
            'siret.size'  => 'Le SIRET doit contenir exactement 14 chiffres.',
            'siret.regex' => 'Le SIRET ne doit contenir que des chiffres.',
        ]);

        $vendeur = Auth::user()->vendeur;

        // Vérification VIES non bloquante (on enregistre le statut)
        $viesResult = $this->vies->validate($request->numero_tva);

        $vendeur->update([
            'raison_sociale'  => $request->raison_sociale,
            'forme_juridique' => $request->forme_juridique,
            'siret'           => $request->siret,
            'numero_tva'      => strtoupper(trim($request->numero_tva)),
            'tva_verifiee'    => $viesResult['valid'],
            'pays'            => strtoupper($request->pays),
            'telephone'       => $request->telephone,
            'site_web'        => $request->site_web,
            'etape_onboarding'=> max($vendeur->etape_onboarding, 2),
        ]);

        $msg = $viesResult['valid']
            ? '✅ TVA vérifiée via VIES — Entreprise : ' . ($viesResult['name'] ?? $request->raison_sociale)
            : '⚠️ TVA non confirmée par VIES (le dossier sera examiné manuellement).';

        return redirect()->route('vendeur.onboarding.etape', 2)->with('vies_info', $msg);
    }

    // ─────────────────────────────────────────────────────────────
    //  Étape 2 — Représentant légal
    // ─────────────────────────────────────────────────────────────

    public function saveEtape2(Request $request): RedirectResponse
    {
        $request->validate([
            'nom_dirigeant'     => ['required', 'string', 'max:255'],
            'fonction_dirigeant'=> ['required', 'string', 'max:100'],
            'email_commercial'  => ['nullable', 'email', 'max:255'],
        ]);

        $vendeur = Auth::user()->vendeur;

        $vendeur->update([
            'nom_dirigeant'      => $request->nom_dirigeant,
            'fonction_dirigeant' => $request->fonction_dirigeant,
            'email_commercial'   => $request->email_commercial,
            'etape_onboarding'   => max($vendeur->etape_onboarding, 3),
        ]);

        return redirect()->route('vendeur.onboarding.etape', 3);
    }

    // ─────────────────────────────────────────────────────────────
    //  Étape 3 — Documents & Certifications
    // ─────────────────────────────────────────────────────────────

    public function saveEtape3(Request $request): RedirectResponse
    {
        $request->validate([
            'kbis'                     => ['required', 'file', 'mimes:pdf', 'max:5120'],
            'rc_pro'                   => ['required', 'file', 'mimes:pdf', 'max:5120'],
            'certificats_ce'           => ['nullable', 'array', 'max:20'],
            'certificats_ce.*'         => ['file', 'mimes:pdf', 'max:10240'],
            'fiches_ppe2'              => ['nullable', 'array', 'max:20'],
            'fiches_ppe2.*'            => ['file', 'mimes:pdf', 'max:10240'],
            'garanties_constructeur'   => ['nullable', 'array', 'max:20'],
            'garanties_constructeur.*' => ['file', 'mimes:pdf', 'max:10240'],
        ], [
            'kbis.required'   => "L'extrait Kbis est obligatoire.",
            'rc_pro.required' => "L'attestation RC Pro est obligatoire.",
        ]);

        $vendeur = Auth::user()->vendeur;

        // Helper pour stocker un fichier et créer/remplacer un Document
        $store = function ($file, TypeDocument $type) use ($vendeur) {
            $path     = $file->store("documents/{$vendeur->id}", 'public');
            $original = $file->getClientOriginalName();
            Document::create([
                'vendeur_id'   => $vendeur->id,
                'type'         => $type,
                'fichier'      => $path,
                'nom_original' => $original,
                'valide'       => false,
            ]);
        };

        // Documents uniques (on supprime l'ancien si existant)
        foreach ([
            'kbis'   => TypeDocument::KBIS,
            'rc_pro' => TypeDocument::RC_PRO,
        ] as $field => $type) {
            if ($request->hasFile($field)) {
                $vendeur->documents()->where('type', $type)->get()->each(function ($doc) {
                    Storage::disk('public')->delete($doc->fichier);
                    $doc->delete();
                });
                $store($request->file($field), $type);
            }
        }

        // Documents multi-upload
        foreach ([
            'certificats_ce'         => TypeDocument::CERTIFICAT_CE,
            'fiches_ppe2'            => TypeDocument::PPE2,
            'garanties_constructeur' => TypeDocument::GARANTIE_CONSTRUCTEUR,
        ] as $field => $type) {
            if ($request->hasFile($field)) {
                foreach ($request->file($field) as $file) {
                    $store($file, $type);
                }
            }
        }

        $vendeur->update(['etape_onboarding' => max($vendeur->etape_onboarding, 4)]);

        return redirect()->route('vendeur.onboarding.etape', 4)
            ->with('success', 'Documents enregistrés avec succès.');
    }

    // ─────────────────────────────────────────────────────────────
    //  Étape 4 — Logistique lourde
    // ─────────────────────────────────────────────────────────────

    public function saveEtape4(Request $request): RedirectResponse
    {
        $request->validate([
            'incoterm_preference'    => ['required', 'string', 'in:DDP,EXW,DAP,FCA,LIBRE'],
            'incoterm_notes'         => ['nullable', 'string', 'max:1000'],
            'moq'                    => ['nullable', 'integer', 'min:1'],
            'delai_traitement_jours' => ['nullable', 'integer', 'min:1', 'max:90'],
            'politique_retour'       => ['nullable', 'string', 'max:2000'],
            // Matrice CSV
            'matrice_transport'      => ['nullable', 'file', 'mimes:csv,txt,xlsx', 'max:2048'],
            // Lignes manuelles (optionnel si CSV)
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
            // Supprimer les lignes manuelles existantes
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
    //  Étape 5 — RIB & Finalisation
    // ─────────────────────────────────────────────────────────────

    public function saveEtape5(Request $request): RedirectResponse
    {
        $request->validate([
            'rib'                  => ['required', 'string', 'max:34', 'regex:/^[A-Z]{2}\d{2}[A-Z0-9]{1,30}$/'],
            'informations_legales' => ['nullable', 'string', 'max:3000'],
            'consent_final'        => ['accepted'],
        ], [
            'rib.regex'         => "L'IBAN doit commencer par 2 lettres (ex : FR76…).",
            'consent_final.accepted' => 'Vous devez accepter les conditions avant de soumettre.',
        ]);

        $vendeur = Auth::user()->vendeur;

        $vendeur->update([
            'rib'                  => strtoupper(str_replace(' ', '', $request->rib)),
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
        $vendeur = Auth::user()->vendeur;
        return view('vendeur.onboarding.pending', compact('vendeur'));
    }

    public function rejected(): View
    {
        $vendeur = Auth::user()->vendeur;
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
            $data = array_combine($header, array_pad($row, count($header), null));

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
