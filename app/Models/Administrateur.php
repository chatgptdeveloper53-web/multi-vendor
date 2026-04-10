<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Administrateur extends User
{
    /**
     * Tous les admins partagent la table `users`.
     */
    protected $table = 'users';

    protected $attributes = [
        'role' => 'admin',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('admin', function (Builder $builder) {
            $builder->where('role', 'admin');
        });
    }
}
