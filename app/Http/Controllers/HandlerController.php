<?php

namespace App\Http\Controllers;

use App\Models\Scheme;
use Illuminate\Support\Str;

class HandlerController extends Controller
{
    private function sanitize_class_name($input)
    {
        // Remove any characters that aren't allowed in class names (including Unicode punctuation)
        $sanitized = preg_replace('/[^\p{L}\p{N}\s_\x7f-\xff]/u', '', $input);

        // Ensure the first character is valid (letter or underscore)
        if (!preg_match('/^[\p{L}_\x7f-\xff]/u', $sanitized)) {
            // If not, prepend an underscore
            $sanitized = '_' . $sanitized;
        }

        // Convert to StudlyCase
        $sanitized = Str::studly($sanitized);

        return $sanitized;
    }

    public function index()
    {
        // get schemes
        $schemes = Scheme::all();

        return ['schemes' => $schemes];
    }
}
