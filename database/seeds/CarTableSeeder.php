<?php
use Illuminate\Database\Seeder;

class CarTableSeeder extends Seeder{
    public function run(){
        DB::table('car')->delete();
        DB::table('car')->insert([
            'brand'=>'Honda',
            'type'=>'civic',
            'year'=>'2016',
            'color'=>'Black',
            'plate'=>'D 1234 HND',
        ]);
    }
}