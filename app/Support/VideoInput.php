<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class VideoInput
{
    public static function validated(Request $request): array
    {
        $data = $request->validate([
            'url' => ['present', 'nullable', 'string', 'max:2048'],
            'author' => ['nullable', 'string', 'max:255'],
        ]);

        $video = [];
        if ($data['url'] !== null) {
            $parts = parse_url($data['url']);
            $host = strtolower($parts['host'] ?? '');
            $path = $parts['path'] ?? '';
            parse_str($parts['query'] ?? '', $query);
            $videoId = match ($host) {
                'youtu.be' => substr($path, 1),
                'www.youtube.com', 'youtube.com', 'm.youtube.com' => $path === '/watch' ? ($query['v'] ?? null) : null,
                default => null,
            };
            $start = $query['t'] ?? $query['start'] ?? '0';
            if (($parts['scheme'] ?? '') !== 'https' || isset($parts['user']) || isset($parts['port'])
                || ! is_string($videoId) || ! preg_match('/\A[A-Za-z0-9_-]{11}\z/', $videoId)
                || ! is_string($start) || ! preg_match('/\A([0-9]{1,9})s?\z/', $start, $seconds)) {
                throw ValidationException::withMessages(['url' => 'Informe um link HTTPS do YouTube. Se usar um tempo de início, informe-o em segundos.']);
            }
            $video = ['id' => $videoId, 'start' => (int) $seconds[1], 'author' => $data['author'] ?? null];
        }

        return $video;
    }
}
