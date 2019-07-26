<?php

namespace App\Http\Controllers\Czf;
use App\Good;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GoodsController extends Controller
{
    /**
     * GoodsController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @see 商品详情
     */
    public function goodsDetail(int $id,Good $good)
    {
        $oGoods = $good->findOrFail($id);
        return view('czf.goodsdetail',compact('oGoods'));
    }
}
