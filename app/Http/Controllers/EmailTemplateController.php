<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use App\Services\EmailHtmlRenderer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use ZipArchive;

class EmailTemplateController extends Controller
{
    protected EmailHtmlRenderer $renderer;

    public function __construct(EmailHtmlRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * Display gallery of templates & builder entry
     */
    public function index(Request $request): Response
    {
        $userId = $request->user()?->id;

        $predefined = EmailTemplate::where('is_predefined', true)
            ->orderBy('id', 'asc')
            ->get();

        $userTemplates = $userId
            ? EmailTemplate::where('user_id', $userId)->orderBy('updated_at', 'desc')->get()
            : collect([]);

        return Inertia::render('Templates/Index', [
            'predefined' => $predefined,
            'userTemplates' => $userTemplates,
            'isGuest' => !$userId,
        ]);
    }

    /**
     * Editor interface
     */
    public function editor(Request $request, ?EmailTemplate $template = null): Response
    {
        return Inertia::render('Editor/Builder', [
            'initialTemplate' => $template ? [
                'id' => $template->id,
                'name' => $template->name,
                'slug' => $template->slug,
                'category' => $template->category,
                'is_predefined' => $template->is_predefined,
                'design_json' => $template->design_json,
            ] : null,
            'auth' => [
                'user' => $request->user(),
            ],
        ]);
    }

    /**
     * Save/Create a template
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'design_json' => 'required|array',
        ]);

        $compiledHtml = $this->renderer->render($validated['design_json']);

        $template = EmailTemplate::create([
            'user_id' => $request->user()?->id,
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']) . '-' . Str::random(6),
            'category' => $validated['category'] ?? 'Custom',
            'is_predefined' => false,
            'design_json' => $validated['design_json'],
            'compiled_html' => $compiledHtml,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Template saved successfully!',
            'template' => $template,
        ]);
    }

    /**
     * Update an existing template
     */
    public function update(Request $request, EmailTemplate $template): JsonResponse
    {
        // If guest or trying to overwrite a predefined template, save as a new copy
        if ($template->is_predefined || ($template->user_id && $template->user_id !== $request->user()?->id)) {
            return $this->store($request);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'design_json' => 'required|array',
        ]);

        $compiledHtml = $this->renderer->render($validated['design_json']);

        $template->update([
            'name' => $validated['name'],
            'category' => $validated['category'] ?? $template->category,
            'design_json' => $validated['design_json'],
            'compiled_html' => $compiledHtml,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Template updated successfully!',
            'template' => $template,
        ]);
    }

    /**
     * Duplicate an existing template
     */
    public function duplicate(Request $request, EmailTemplate $template): JsonResponse
    {
        $newTemplate = EmailTemplate::create([
            'user_id' => $request->user()?->id,
            'name' => $template->name . ' (Copy)',
            'slug' => Str::slug($template->name) . '-copy-' . Str::random(6),
            'category' => $template->category,
            'is_predefined' => false,
            'design_json' => $template->design_json,
            'compiled_html' => $template->compiled_html,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Template duplicated successfully!',
            'template' => $newTemplate,
        ]);
    }

    /**
     * Delete template
     */
    public function destroy(Request $request, EmailTemplate $template): JsonResponse
    {
        if ($template->is_predefined) {
            return response()->json([
                'success' => false,
                'message' => 'Predefined templates cannot be deleted.',
            ], 403);
        }

        if ($template->user_id && $template->user_id !== $request->user()?->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.',
            ], 403);
        }

        $template->delete();

        return response()->json([
            'success' => true,
            'message' => 'Template deleted successfully!',
        ]);
    }

    /**
     * Render design JSON to HTML on the fly
     */
    public function renderHtml(Request $request): JsonResponse
    {
        $design = $request->input('design_json', []);
        $html = $this->renderer->render($design);

        return response()->json([
            'html' => $html,
        ]);
    }

    /**
     * Export template as a single HTML download
     */
    public function exportHtml(Request $request)
    {
        $design = $request->input('design_json', []);
        $name = Str::slug($request->input('name', 'email-template')) . '.html';

        $html = $this->renderer->render($design);

        return response($html, 200, [
            'Content-Type' => 'text/html',
            'Content-Disposition' => "attachment; filename=\"{$name}\"",
        ]);
    }

    /**
     * Export as a ZIP package
     */
    public function exportZip(Request $request)
    {
        $design = $request->input('design_json', []);
        $slug = Str::slug($request->input('name', 'email-template'));
        $html = $this->renderer->render($design);

        $tempFile = tempnam(sys_get_temp_dir(), 'mail_zip_');
        $zip = new ZipArchive();

        if ($zip->open($tempFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            $zip->addFromString('index.html', $html);
            $zip->addFromString('template-design.json', json_encode($design, JSON_PRETTY_PRINT));
            $zip->addFromString('README.txt', "Email Template exported from Open-Source Email Template Builder.\nUse index.html directly in Mailchimp, Resend, Brevo, Gmail, or your custom application.\nGitHub: https://github.com/huzaifaazeem869");
            $zip->close();
        }

        return response()->download($tempFile, "{$slug}.zip")->deleteFileAfterSend(true);
    }
}
