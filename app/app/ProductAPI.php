<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductAPI extends Model
{
    protected $fillable = [
        'name', 'detail', 'id'
    ];
}
