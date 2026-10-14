<?php

namespace Tests\Feature;

use App\Models\EmailTemplate;
use App\Models\User;
use App\Services\EmailHtmlRenderer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmailTemplateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\EmailTemplateSeeder::class);
    }

    public function test_template_gallery_can_be_rendered(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $this->assertDatabaseCount('email_templates', 10);
    }

    public function test_blank_editor_can_be_accessed(): void
    {
        $response = $this->get('/editor');

        $response->assertStatus(200);
    }

    public function test_predefined_template_editor_can_be_accessed(): void
    {
        $template = EmailTemplate::where('slug', 'welcome-email')->first();
        $this->assertNotNull($template);

        $response = $this->get("/editor/{$template->slug}");
        $response->assertStatus(200);
    }

    public function test_html_can_be_rendered_via_api(): void
    {
        $response = $this->postJson('/api/templates/render-html', [
            'design_json' => [
                'brand' => [
                    'canvasWidth' => '600px',
                    'canvasBg' => '#ffffff',
                    'bodyBg' => '#f3f4f6',
                ],
                'blocks' => [
                    [
                        'type' => 'heading',
                        'props' => [
                            'text' => 'Hello World Test',
                            'level' => 'h1',
                            'align' => 'center',
                        ]
                    ],
                    [
                        'type' => 'button',
                        'props' => [
                            'text' => 'Click Me',
                            'url' => 'https://example.com',
                        ]
                    ]
                ]
            ]
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['html']);
        $this->assertStringContainsString('Hello World Test', $response->json('html'));
        $this->assertStringContainsString('Click Me', $response->json('html'));
    }

    public function test_template_can_be_saved(): void
    {
        $response = $this->postJson('/api/templates/save', [
            'name' => 'Agency Custom Promo',
            'category' => 'Marketing',
            'design_json' => [
                'brand' => ['company' => 'AgencyX'],
                'blocks' => [
                    ['type' => 'heading', 'props' => ['text' => 'Exclusive Offer']]
                ]
            ]
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('email_templates', [
            'name' => 'Agency Custom Promo',
            'is_predefined' => false,
        ]);
    }
}
