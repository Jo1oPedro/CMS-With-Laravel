<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Visitor;

class PagesController extends Controller
{

    public function index(Request $request, $slug) {
        //$string = explode(" ", $slug);
        $page = Page::where('slug', $slug)->first();
        if($page) {
            $tableVisitor = new Visitor;
            $visitors = Visitor::where('ip', $request->ip())->first();
            if($visitors) {
                $visitorsPage = Visitor::
                where([
                    ['ip', '=', $request->ip()],
                    ['page', '=', $slug]
                ])
                ->first();

                if(!$visitorsPage) {
                    $tableVisitor->ip = $request->ip();
                    $tableVisitor->date_access = date('Y-m-d H:i:s');
                    $tableVisitor->page = $slug;
                    $tableVisitor->save();
                }
            } else {
                $tableVisitor->ip = $request->ip();
                $tableVisitor->date_access = date('Y-m-d H:i:s');
                $tableVisitor->page = $slug;
                $tableVisitor->save();
            }
            return view('site.page', [
                'page' => $page
            ]);

        }
        abort(404);
    }
}
