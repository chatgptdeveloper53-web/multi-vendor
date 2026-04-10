<?php

namespace App\Enums;

enum StatutCommande: string
{
    case EN_COURS = 'EN_COURS';
    case LIVREE   = 'LIVREE';
    case ANNULEE  = 'ANNULEE';
}
