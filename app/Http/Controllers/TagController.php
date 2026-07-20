<?php

namespace App\Http\Controllers;

use App\Services\TagService;
use Illuminate\Http\RedirectResponse;

class TagController extends Controller
{
    public function show(string $tag, TagService $tags): RedirectResponse
    {
        abort_if($tags->findBySlug($tag) === null, 404);

        return to_route('posts.index', ['tags' => $tag]);
    }
}
