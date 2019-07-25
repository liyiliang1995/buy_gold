<?php

namespace App;
use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class Member extends Model implements AuthenticatableContract,CanResetPasswordContract
{
    use Authenticatable,CanResetPassword;
    /**
     * @var string
     */
    protected $table = 'member';
    /**
     * @var array
     */
    protected $fillable = ['name','password','phone'];

    /**
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * @param string $phone
     * @return int
     * @see 检测手机号码是否存在
     */
    public function isExistsPhone(string $phone):int
    {
        return $this->where('phone',$phone)->count() ?? 0;
    }
}
