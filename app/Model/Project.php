<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;
     protected $connection = 'mysql';
     protected $table      = 'projects';
     protected $primaryKey = 'id';
     protected $fillable   = [
        'name',
        'description',
        'status'
        ];

     protected $dates = ['deleted_at'];
}
