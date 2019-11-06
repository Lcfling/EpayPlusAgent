<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Agcount extends Model
{
    protected $table = 'agent_count';
    protected $primaryKey = 'id';
    public $timestamps = false;
}