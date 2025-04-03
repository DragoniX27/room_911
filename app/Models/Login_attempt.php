<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Login_attempt extends Model
{
    protected $fillable = ['user_id', 'email', 'ip_address', 'user_agent', 'status', 'created_at'];

    public $timestamps = false;
}
