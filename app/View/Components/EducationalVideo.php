<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class EducationalVideo extends Component
{
    public ?string $embedUrl = null;

    public ?string $watchUrl = null;

    public ?string $author = null;

    public function __construct(string $module, string $slug, public string $topic, ?array $video = null)
    {
        $video ??= config('educational_videos')[$module][$slug] ?? [];
        $id = $video['id'] ?? null;
        $start = $video['start'] ?? 0;
        if (! is_string($id) || ! preg_match('/\A[A-Za-z0-9_-]{11}\z/', $id) || ! is_int($start) || $start < 0) {
            return;
        }

        $this->embedUrl = 'https://www.youtube-nocookie.com/embed/'.$id.($start ? '?start='.$start : '');
        $this->watchUrl = 'https://www.youtube.com/watch?v='.$id.($start ? '&t='.$start.'s' : '');
        $this->author = $video['author'] ?? null;
    }

    public function render(): View
    {
        return view('components.educational-video');
    }
}
