<?php

namespace App\Services;

class ReturnReferences
{
    public static function forModality(?string $slug): array
    {
        $catalog = config('return_references');
        $result = [];
        foreach ($catalog['modalities'][$slug] ?? [] as $key) {
            $record = $catalog['indicators'][$key];
            $url = parse_url($record['url']);
            if (($url['scheme'] ?? '') !== 'https' || ! in_array($url['host'] ?? '', ['www.bcb.gov.br', 'www4.bcb.gov.br', 'arquivos.b3.com.br'], true) || isset($url['user']) || isset($url['port'])) {
                continue;
            }
            $date = $record['date'];
            $fresh = $date && $date <= today()->toDateString() && $date >= today()->subDays($record['review_days'])->toDateString();
            $id = $key.'-'.substr(hash('sha256', json_encode($record, JSON_THROW_ON_ERROR)), 0, 16);
            $value = $record['value'];
            $result[$id] = $record + [
                'fresh' => (bool) $fresh,
                'usable' => $fresh && is_numeric($value) && $value > -100 && $value <= 100,
            ];
        }

        return $result;
    }
}
