<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Visitor;
use App\Models\Page;
use App\Models\User;

class AdminHomeController extends Controller
{
    
    public function __construct() {
        $this->middleware('auth');
        //se o usuario não está logado ele chama a rota com name login
        // essa rota está na web.php
    }

    public function index(Request $request) {
        
        $visitsCount = 0;
        $onlineCount = 0;
        $pageCount = 0;
        $userCount = 0;
        $interval = intval($request->input('interval', 1));
        // Contagem de Visitantes
        if($interval > 6 || $interval < 1) {
            $interval = 6;
        }
        $dateInterval = date('Y-m-d H:i:s', strtotime('-'.$interval.'months'));
        $visitsCount = Visitor::where('date_access', '>=', $dateInterval)->count();
        
        // Contagem de Usuários Online
        $dateLimit = date('Y-m-d H:i:s', strtotime('-5 minutes'));
        $onlineList = Visitor::select('ip')->where('date_access', '>=', $dateLimit)->groupBy('ip')->get();
        $onlineCount = count($onlineList);


        // Contagem de Páginas
        $pageCount = Page::count();

        // Contagem de Usuários
        $userCount = User::count();

        // Contagem para o pagePie
        $pagePie = [];
        $pagePieColor = [];

        $visitsAll = Visitor::selectRaw('page, count(page) as val')->groupBy('page')->where('date_access', '>=', $dateInterval)->get();
        foreach($visitsAll as $visit) {
            $pagePie[ $visit['page'] ] = intval($visit['val']);
            $pagePieColor[] = 'rgba('.rand(0, 255).', '.rand(0,255).', '.rand(0,255). ')';
        }

        $pageLabels = json_encode( array_keys($pagePie) );
        $pageValues = json_encode( array_Values($pagePie) );
        $pageColor = json_encode( array_Values($pagePieColor));

        return view('site.home', [
            'visitsCount' => $visitsCount,
            'onlineCount' => $onlineCount,
            'pageCount' => $pageCount,
            'userCount' => $userCount,
            'pageLabels' => $pageLabels,
            'pageValues' => $pageValues,
            'pageColor' => $pageColor,
            'dateInterval' => $interval
        ]);
    }

}
