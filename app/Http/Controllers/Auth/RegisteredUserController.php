<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vendeur;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Affiche le formulaire d'inscription.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Enregistre un nouvel utilisateur.
     * Si le rôle est "vendeur", crée automatiquement le profil vendeur.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nom'      => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role'     => ['required', 'in:acheteur,vendeur'],
        ]);

        $user = User::create([
            'nom'      => $request->nom,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        // Si l'utilisateur est un vendeur, initialiser son profil
        if ($user->role === 'vendeur') {
            Vendeur::create([
                'user_id'           => $user->id,
                'profil_complet'    => false,
                'statut_onboarding' => 'EN_ATTENTE',
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        // Les vendeurs démarrent le wizard d'onboarding
        if ($user->role === 'vendeur') {
            return redirect()->route('vendeur.onboarding.etape', 1);
        }

        return redirect()->route('home');
    }
}
