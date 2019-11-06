<?php

namespace App\Models;
use App\Models\Interfaces\AdminUsersInterface;
use App\Models\Traits\AdminUsersTrait;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
class User extends Model implements AuthenticatableContract, CanResetPasswordContract, AdminUsersInterface
{
    use Authenticatable, CanResetPassword, AdminUsersTrait;
    protected $table = 'agent';
    protected $userInfo;
    protected $primaryKey = 'id';
    public $timestamps = false;
}
