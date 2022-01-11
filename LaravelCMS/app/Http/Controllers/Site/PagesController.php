<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;

class PagesController extends Controller
{
    public function index($slug) {
        //$string = explode(" ", $slug);
        $page = Page::where('slug', $slug)->first();
        if($page) {
            return view('site.page', [
                'page' => $page
            ]);

        }
        abort(404);
    }
}
