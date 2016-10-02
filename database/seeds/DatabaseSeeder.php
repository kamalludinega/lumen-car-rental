<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $this->call('ClientTableSeeder');
         $this->call('CarTableSeeder');
         $this->call('RentalTableSeeder');
    }
}
