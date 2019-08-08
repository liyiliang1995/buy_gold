<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Illuminate\Support\Arr;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        $title = "<h1 style='width: 100%;text-align: center;line-height: 80px'>欢迎登陆翠竹坊管理中心</h1> <style>.row{ width: 100%; margin: 0 auto;background: #fff; line-height: 70px;font-size: 18px;}.col-md-6{border: 1px solid #eee}</style>";
//dd($this->ajaxGetGoldPool()['0']);
        return $content
            ->title('翠竹坊管理中心')
            ->description('欢迎您')
            ->breadcrumb(['text' => '欢迎页'])
            ->row($title)
            ->row(function (Row $row) {
                $row->column(12, '翠竹坊数据统计：');
                $row->column(6, $this->ajaxGetGoldPool()['0']['name']);
                $row->column(6, $this->ajaxGetGoldPool()['0']['value']);
                $row->column(6, $this->ajaxGetGoldPool()['1']['name']);
                $row->column(6, $this->ajaxGetGoldPool()['1']['value']);
                $row->column(6, $this->ajaxGetGoldPool()['2']['name']);
                $row->column(6, $this->ajaxGetGoldPool()['2']['value']);
                $row->column(6, $this->ajaxGetGoldPool()['3']['name']);
                $row->column(6, $this->ajaxGetGoldPool()['3']['value']);
                $row->column(12,date('Y-m-d H:i:s'));

            });


    }

    /**
     * @获取金币池剩余金币数量
     */
    public static function ajaxGetGoldPool()
    {

        $aData          = gold_compute();
        $aData['test']  = array_sum($aData);
        $aData['redis'] = get_gold_pool();

        $envs = [
            ['name' => '金币池总数量', 'value' => $aData['test']],
            ['name' => '金币池剩余数', 'value' => $aData['gold']],
            ['name' => '用户拥有金币数', 'value' => $aData['user_sum_gold']],
            ['name' => '金币燃烧数', 'value' =>$aData['burn_gold']],
        ];

        return $envs;
    }


}
