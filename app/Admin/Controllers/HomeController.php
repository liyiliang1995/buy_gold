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
        $title = "<h1 style='width: 100%;text-align: center;line-height: 80px'>欢迎登陆翠竹坊管理中心</h1>";

        return $content
            ->title('翠竹坊管理中心')
            ->description('欢迎您')
            ->breadcrumb(['text' => '欢迎页'])
            ->row($title)
            ->row(function (Row $row) {
                $row->column(12, function (Column $column) {
                    $column->append(Dashboard::ajaxGetGoldPool());
                });

            });


    }


}
