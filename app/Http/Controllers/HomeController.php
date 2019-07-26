<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Good;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Good $good)
    {
        if (\Auth::guard()->user()->status == 0)
            return redirect('/userset');
        else {
            $aConfig = getConfigByType(1);
            $aGoods = $this->getGoodsLoic($good)->query(['_sort'=>'updated_at,desc']);
            return view('czf.home',compact('aConfig','aGoods'));
        }
    }

    /**
     * @param object $oModel
     */
    public function getGoodsLoic(object $oModel)
    {
        return new \App\Logics\GoodsLogic($oModel);
    }


}
