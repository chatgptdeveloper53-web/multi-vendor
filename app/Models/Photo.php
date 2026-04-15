<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Photo extends Model
{
    protected $fillable = [
        'produit_id',
        'url',
        'ordre',
        'principale',
    ];

    protected function casts(): array
    {
        return [
            'ordre'      => 'integer',
            'principale' => 'boolean',
        ];
    }

    public function produit(): BelongsTo
    {
        return $this->belongsTo(Produit::class);
    }

    public function assetUrl(): string
    {
        return asset('storage/' . $this->url);
    }

    public function deleteFile(): void
    {
        Storage::disk('public')->delete($this->url);
    }
}
