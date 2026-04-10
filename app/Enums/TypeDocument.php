<?php

namespace App\Enums;

enum TypeDocument: string
{
    case ID                    = 'ID';
    case KBIS                  = 'KBIS';
    case CERTIFICAT_CE         = 'CERTIFICAT_CE';
    case PPE2                  = 'PPE2';
    case TVA                   = 'TVA';
    case GARANTIE_CONSTRUCTEUR = 'GARANTIE_CONSTRUCTEUR';
    case RC_PRO                = 'RC_PRO';

    public function label(): string
    {
        return match($this) {
            self::ID                    => "Pièce d'identité",
            self::KBIS                  => 'Extrait Kbis',
            self::CERTIFICAT_CE         => 'Certificat CE',
            self::PPE2                  => 'Fiche Certisolis / PPE2',
            self::TVA                   => 'Attestation TVA',
            self::GARANTIE_CONSTRUCTEUR => 'Garantie constructeur',
            self::RC_PRO                => 'Attestation RC Pro',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::ID                    => 'solar:user-id-line-duotone',
            self::KBIS                  => 'solar:buildings-line-duotone',
            self::CERTIFICAT_CE         => 'solar:shield-check-line-duotone',
            self::PPE2                  => 'solar:sun-line-duotone',
            self::TVA                   => 'solar:document-text-line-duotone',
            self::GARANTIE_CONSTRUCTEUR => 'solar:medal-ribbons-star-line-duotone',
            self::RC_PRO                => 'solar:shield-warning-line-duotone',
        };
    }

    /** Types pouvant être uploadés en masse (multi-fichiers) */
    public function isMultiUpload(): bool
    {
        return in_array($this, [
            self::CERTIFICAT_CE,
            self::PPE2,
            self::GARANTIE_CONSTRUCTEUR,
        ]);
    }
}
