<?php
/**
 * Created by PhpStorm.
 * User: vti-eg
 * Date: 30/09/16
 * Time: 19:53
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    //table name
    protected $table = 'car';

    //disable default timestamp
    public $timestamps = false;

    //field
    protected $fillable = ['brand','type','year','color','plate'];

    public function histories(){
        return $this->hasMany('App\Rental','car-id');
    }
}