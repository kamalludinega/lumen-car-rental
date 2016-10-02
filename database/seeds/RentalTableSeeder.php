<?php
use Illuminate\Database\Seeder;
class RentalTableSeeder extends Seeder{
    public function run(){
        DB::table('rental')->delete();
        DB::table('rental')->insert([
            'car-id'=>1,
            'client-id'=>1,
            'date-from'=>'2016-08-15',
            'date-to'=>'2016-08-17',
        ]);
    }
}