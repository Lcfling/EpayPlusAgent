<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Draw extends Model
{
    protected $table = 'agent_withdraw';
    protected $primaryKey = 'id';
    public $timestamps = false;
}