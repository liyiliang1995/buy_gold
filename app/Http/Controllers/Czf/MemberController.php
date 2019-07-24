<?php

namespace App\Http\Controllers\Czf;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MemberController extends Controller
{
    public function getlogin()
    {
        return view('czf.login');
    }

    public function getUserSet()
    {
        dd(config('czf.test'));
        return view('czf.userset');
    }
}
