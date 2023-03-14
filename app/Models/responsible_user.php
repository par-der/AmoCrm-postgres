<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class responsible_user extends Model
{
    use HasFactory;
    protected $fillable = ['id','name','email'];
    protected $table = 'responsible_users';
}
