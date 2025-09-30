<?php

namespace App\Http\Controllers;

use App\Models\Mastermind;
use App\Models\Scheme;
use App\Models\Set;
use App\Models\UserPermission;
use Illuminate\Http\Client\Request;
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

    private function buildHandlerName($rec)
    {
        $name = Str::studly($this->sanitize_class_name($rec->name));
        $set = Set::where('value', $rec->set)->first();
        $label = Str::studly($this->sanitize_class_name($set->label));
        return "{$name}_{$label}";
    }

    public function index()
    {
        $user = request()->user();

        $permissions = UserPermission::where('user_id', $user->id)
            ->where('permission_level', 'handlers')
            ->count();

        if ($permissions == 0) {
            return [
                'status' => 'error',
                'message' => 'Unauthorized'
            ];
        }


        $handlers = [];

        // get schemes
        $handlers['schemes'] = Scheme::all()
            ->map(function ($rec) {
                return ['id' => uniqid(), 'name' => $this->buildHandlerName($rec)];
            })
            ->filter(function ($rec) {
                $exists = file_exists(dirname(__DIR__, 2) . '/Handlers/Schemes/' . $rec['name'] . '.php');
                return !$exists;
            })
            ->values()
            ->toArray();

        // get masterminds
        $handlers['masterminds'] = Mastermind::all()
            ->map(function ($rec) {
                return ['id' => uniqid(), 'name' => $this->buildHandlerName($rec)];
            })
            ->filter(function ($rec) {
                $exists = file_exists(dirname(__DIR__, 2) . '/Handlers/Masterminds/' . $rec['name'] . '.php');
                return !$exists;
            })
            ->values()
            ->toArray();


        return ['status' => 'success', 'handlers' => $handlers];
    }
}
