<?php

namespace App\Enums;

enum StatutDossier: string
{
    case EN_ATTENTE = 'EN_ATTENTE';
    case VALIDE     = 'VALIDE';
    case REJETE     = 'REJETE';
}
