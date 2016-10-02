<?php
/**
 * Created by PhpStorm.
 * User: vti-eg
 * Date: 30/09/16
 * Time: 19:53
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    //table name
    protected $table = 'rental';

    //disable default timestamp
    public $timestamps = false;

    //field
    protected $fillable = ['car-id','client-id','date-from','date-to'];

}