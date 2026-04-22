<?php

namespace App\Enums;

enum TypeDocument: string
{
    case ID                       = 'ID';
    case KBIS                     = 'KBIS';
    case CERTIFICAT_CE            = 'CERTIFICAT_CE';
    case PPE2                     = 'PPE2';
    case TVA                      = 'TVA';
    case GARANTIE_CONSTRUCTEUR    = 'GARANTIE_CONSTRUCTEUR';
    case RC_PRO                   = 'RC_PRO';
    case STATUTS_SOCIETE          = 'STATUTS_SOCIETE';
    case PIECE_IDENTITE_DIRIGEANT = 'PIECE_IDENTITE_DIRIGEANT';
    case RIB_BANCAIRE             = 'RIB_BANCAIRE';

    public function label(): string
    {
        return match($this) {
            self::ID                       => "Pièce d'identité",
            self::KBIS                     => 'K-Bis (moins de 3 mois)',
            self::CERTIFICAT_CE            => 'Certificat CE',
            self::PPE2                     => 'Fiche Certisolis / PPE2',
            self::TVA                      => 'Attestation TVA',
            self::GARANTIE_CONSTRUCTEUR    => 'Garantie constructeur',
            self::RC_PRO                   => 'Attestation RC Pro',
            self::STATUTS_SOCIETE          => 'Statuts de la société',
            self::PIECE_IDENTITE_DIRIGEANT => "Pièce d'identité dirigeant",
            self::RIB_BANCAIRE             => 'RIB bancaire',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::ID                       => 'solar:user-id-line-duotone',
            self::KBIS                     => 'solar:buildings-line-duotone',
            self::CERTIFICAT_CE            => 'solar:shield-check-line-duotone',
            self::PPE2                     => 'solar:sun-line-duotone',
            self::TVA                      => 'solar:document-text-line-duotone',
            self::GARANTIE_CONSTRUCTEUR    => 'solar:medal-ribbons-star-line-duotone',
            self::RC_PRO                   => 'solar:shield-warning-line-duotone',
            self::STATUTS_SOCIETE          => 'solar:document-add-line-duotone',
            self::PIECE_IDENTITE_DIRIGEANT => 'solar:user-id-bold-duotone',
            self::RIB_BANCAIRE             => 'solar:card-2-line-duotone',
        };
    }

    /** 4 documents obligatoires de l'étape 2 (dans l'ordre affiché) */
    public static function required(): array
    {
        return [
            self::KBIS,
            self::STATUTS_SOCIETE,
            self::PIECE_IDENTITE_DIRIGEANT,
            self::RIB_BANCAIRE,
        ];
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

    /** Types de certifications EnR (étape 3) */
    public static function certifications(): array
    {
        return [self::CERTIFICAT_CE, self::PPE2];
    }
}
