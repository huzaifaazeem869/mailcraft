<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use App\Services\EmailHtmlRenderer;
use App\Services\PredefinedTemplates;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $renderer = new EmailHtmlRenderer();
        $templates = PredefinedTemplates::all();

        foreach ($templates as $data) {
            $compiledHtml = $renderer->render($data['design_json']);

            EmailTemplate::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'name' => $data['name'],
                    'category' => $data['category'],
                    'description' => $data['description'],
                    'thumbnail' => $data['thumbnail'],
                    'is_predefined' => true,
                    'design_json' => $data['design_json'],
                    'compiled_html' => $compiledHtml,
                    'user_id' => null,
                ]
            );
        }
    }
}
