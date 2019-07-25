<?php
/**
 * Created by PhpStorm.
 * User: youxingxiang
 * Date: 2019/7/25
 * Time: 3:19 PM
 */

namespace App\Exceptions;
use Exception;
use Illuminate\Support\Facades\DB;
class CzfException extends Exception
{
    use \App\Traits\Restful;

    /**
     *
     */
    public function report()
    {
        //
    }

    public function render($request)
    {
        return $this->params_error($this->getMessage());
    }
}
