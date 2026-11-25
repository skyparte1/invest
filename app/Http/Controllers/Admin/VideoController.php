<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\Investment;
use App\Support\VideoInput;
use App\View\Components\EducationalVideo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VideoController extends Controller
{
    public function index(Request $request): View
    {
        $module = $request->query('module', 'learn');
        abort_unless(in_array($module, ['learn', 'investments'], true), 404);

        $items = $module === 'learn'
            ? Content::orderBy('title')->paginate(20)
            : Investment::orderBy('name')->paginate(20);

        return view('admin.videos.index', ['module' => $module, 'items' => $items->withQueryString()]);
    }

    public function edit(string $module, int $id): View
    {
        $item = $this->item($module, $id);
        $topic = $module === 'learn' ? $item->title : $item->name;
        $video = new EducationalVideo($module, $item->slug, $topic, $item->video);

        return view('admin.videos.edit', compact('module', 'item', 'topic', 'video'));
    }

    public function update(Request $request, string $module, int $id): RedirectResponse
    {
        $item = $this->item($module, $id);
        $item->video = VideoInput::validated($request);
        $item->save();

        return to_route('admin.videos.edit', [$module, $id])->with('status', 'Vídeo complementar atualizado.');
    }

    private function item(string $module, int $id): Content|Investment
    {
        return $module === 'learn' ? Content::findOrFail($id) : Investment::findOrFail($id);
    }
}
