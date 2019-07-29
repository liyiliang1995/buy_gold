<?php

namespace App\Http\Controllers\Czf;

use Illuminate\Http\Request;
use App\Good;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * HomeController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth','checkmbr']);
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Good $good)
    {
        $aConfig = getConfigByType(1);
        $aGoods = $this->getGoodsLoic($good)->query(['_sort'=>'updated_at,desc']);
        return view('czf.home',compact('aConfig','aGoods'));
    }

    /**
     * @param object $oModel
     */
    public function getGoodsLoic(object $oModel)
    {
        return new \App\Logics\GoodsLogic($oModel);
    }
}
