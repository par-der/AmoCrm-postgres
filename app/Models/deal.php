<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class deal extends Model
{
    use HasFactory;
    protected $fillable = ['id','name','price','responsible_user','companies'];
    protected $table = 'deals';
}
