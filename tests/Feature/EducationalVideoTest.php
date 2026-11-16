<?php

namespace Tests\Feature;

use App\Models\User;
use DOMDocument;
use DOMXPath;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EducationalVideoTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_approved_pages_embed_the_expected_video_and_keep_a_direct_link(): void
    {
        $this->seed();
        $this->actingAs(User::factory()->create());

        foreach ([
            '/aprender/educacao-financeira-por-onde-comecar' => ['0vQ86Tm1AEE', 0],
            '/aprender/orcamento-pessoal' => ['OZ8-ZPZSgX8', 0],
            '/aprender/juros-compostos' => ['X5uT6yG1-1o', 0],
            '/aprender/renda-fixa-e-renda-variavel' => ['WHWrk0e7hgI', 0],
            '/aprender/risco-e-retorno' => ['WHWrk0e7hgI', 123],
            '/aprender/liquidez' => ['xGoVV_BK0UE', 486],
            '/investimentos/poupanca' => ['X1wwqcItjh0', 0],
            '/investimentos/tesouro-selic' => ['xGoVV_BK0UE', 0],
            '/investimentos/cdb' => ['WHWrk0e7hgI', 1308],
            '/investimentos/fundos-de-investimento' => ['WHWrk0e7hgI', 1469],
            '/investimentos/fundos-imobiliarios' => ['TCsDwE1Hlr8', 420],
            '/investimentos/acoes' => ['E-OCg1__mPA', 0],
        ] as $path => [$id, $seconds]) {
            $response = $this->get($path)->assertOk()->assertSee('Fontes e referências')
                ->assertSee('Aprofunde seus conhecimentos com este conteúdo educativo.');
            $doc = new DOMDocument;
            $doc->loadHTML($response->getContent(), LIBXML_NONET | LIBXML_NOERROR | LIBXML_NOWARNING);
            $xpath = new DOMXPath($doc);
            $section = '//section[@aria-labelledby="video-title"]';
            $frames = $xpath->query($section.'//div[@class="ratio ratio-16x9"]/iframe');
            $this->assertCount(1, $frames, $path);
            $frame = $frames->item(0);
            $this->assertSame('https://www.youtube-nocookie.com/embed/'.$id.($seconds ? '?start='.$seconds : ''), $frame->getAttribute('src'));
            $this->assertSame('lazy', $frame->getAttribute('loading'));
            $this->assertNotEmpty($frame->getAttribute('title'));
            $links = $xpath->query($section.'//a[@target="_blank" and @rel="noopener noreferrer" and contains(., "Assistir no YouTube")]');
            $this->assertCount(1, $links);
            $this->assertSame('https://www.youtube.com/watch?v='.$id.($seconds ? '&t='.$seconds.'s' : ''), $links->item(0)->getAttribute('href'));
        }
    }

    public function test_unapproved_urls_and_invalid_offsets_do_not_generate_links(): void
    {
        $this->seed();
        $this->actingAs(User::factory()->create());
        foreach ([['id' => 'https://example.com/video'], ['id' => 'WHWrk0e7hgI', 'start' => -1]] as $invalid) {
            config(['educational_videos.investments.cdb' => $invalid]);
            $this->get('/investimentos/cdb')->assertOk()->assertDontSee('<iframe', false)
                ->assertDontSee('Assistir no YouTube')->assertSee('Vídeo em preparação.');
        }
    }
}
