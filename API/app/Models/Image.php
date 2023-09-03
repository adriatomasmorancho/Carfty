<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{

    //protected $fillable = ['url,name'];
    public $fillable = ['name,url,product_id'];

    use HasFactory;
}
