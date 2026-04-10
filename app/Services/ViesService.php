<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ViesService
{
    /**
     * Endpoint REST officiel de la Commission Européenne.
     * Doc : https://ec.europa.eu/taxation_customs/vies/#/technical-information
     */
    private const BASE_URL = 'https://ec.europa.eu/taxation_customs/vies/rest-api/ms/{cc}/vat/{vat}';

    /**
     * Valide un numéro de TVA intracommunautaire via le système VIES.
     *
     * @param  string $numeroTva  Ex: "FR12345678901" ou "DE123456789"
     * @return array{
     *   valid: bool,
     *   name: string|null,
     *   address: string|null,
     *   country_code: string,
     *   vat_number: string,
     *   error: string|null
     * }
     */
    public function validate(string $numeroTva): array
    {
        $numeroTva = strtoupper(trim(str_replace([' ', '.', '-'], '', $numeroTva)));

        // Séparer le code pays (2 lettres) du numéro
        if (strlen($numeroTva) < 4 || !ctype_alpha(substr($numeroTva, 0, 2))) {
            return $this->error('Format invalide. Exemple attendu : FR12345678901');
        }

        $countryCode = substr($numeroTva, 0, 2);
        $vatNumber   = substr($numeroTva, 2);

        // Pays UE acceptés par VIES
        $euCountries = [
            'AT','BE','BG','CY','CZ','DE','DK','EE','EL','ES',
            'FI','FR','HR','HU','IE','IT','LT','LU','LV','MT',
            'NL','PL','PT','RO','SE','SI','SK','XI',
        ];

        if (!in_array($countryCode, $euCountries)) {
            return $this->error("Pays «{$countryCode}» non couvert par VIES (UE uniquement).");
        }

        try {
            $url = str_replace(['{cc}', '{vat}'], [$countryCode, $vatNumber], self::BASE_URL);

            $response = Http::timeout(8)->get($url);

            if ($response->failed()) {
                return $this->error('Le service VIES est temporairement indisponible. Réessayez dans quelques instants.');
            }

            $data = $response->json();

            return [
                'valid'        => (bool) ($data['valid'] ?? false),
                'name'         => $data['name'] ?? null,
                'address'      => $data['address'] ?? null,
                'country_code' => $countryCode,
                'vat_number'   => $vatNumber,
                'error'        => null,
            ];

        } catch (\Throwable $e) {
            Log::warning('VIES validation failed', ['tva' => $numeroTva, 'error' => $e->getMessage()]);
            return $this->error('Impossible de joindre le service VIES : ' . $e->getMessage());
        }
    }

    private function error(string $message): array
    {
        return [
            'valid'        => false,
            'name'         => null,
            'address'      => null,
            'country_code' => '',
            'vat_number'   => '',
            'error'        => $message,
        ];
    }
}
