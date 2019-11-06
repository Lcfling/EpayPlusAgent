<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $table = 'agent_bank';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $bankInfo;
}