<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Acheteur extends User
{
    /**
     * Tous les acheteurs partagent la table `users`.
     * Sans cette ligne, Laravel cherche une table `acheteurs` qui n'existe pas.
     */
    protected $table = 'users';

    protected $attributes = [
        'role' => 'acheteur',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('acheteur', function (Builder $builder) {
            $builder->where('role', 'acheteur');
        });
    }
}
