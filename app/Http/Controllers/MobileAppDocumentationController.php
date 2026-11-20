<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MobileAppDocumentationController extends Controller
{
    public function show()
    {
        $markdown = File::get(base_path('docs/android-app.md'));

        return view('admin.mobile-app-guide', [
            'documentation' => Str::markdown($markdown, [
                'html_input' => 'strip',
                'allow_unsafe_links' => false,
            ]),
        ]);
    }
}
