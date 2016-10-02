<?php
use Illuminate\Database\Seeder;

class ClientTableSeeder extends Seeder{
    public function run(){
        DB::table('client')->delete();
        DB::table('client')->insert([
            'name'=>'Ahmad',
            'gender'=>'male'
        ]);
    }
}