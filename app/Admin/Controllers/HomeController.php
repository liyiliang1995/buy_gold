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

        return $content
             ->title('翠竹坊管理中心')
            ->description('欢迎您')
            ->breadcrumb(['text'=>'欢迎页']);

    }
}
