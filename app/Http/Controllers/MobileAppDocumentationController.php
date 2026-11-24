<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MobileAppDocumentationController extends Controller
{
    public function show()
    {
        return view('admin.mobile-app-guide', [
            'documentation' => $this->renderMarkdown('android-app.md'),
            'presentation' => $this->renderMarkdown('presentacion-dentissa.md'),
        ]);
    }

    private function renderMarkdown(string $file): string
    {
        $markdown = File::get(base_path('docs/' . $file));
        $markdown = preg_replace_callback(
            '/(!\[[^\]]*\]\()\.\.\/public\/(img\/diagramas\/[A-Za-z0-9._-]+)(\))/u',
            fn (array $matches): string => $matches[1] . asset($matches[2]) . $matches[3],
            $markdown
        );

        return Str::markdown($markdown, [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);
    }
}
