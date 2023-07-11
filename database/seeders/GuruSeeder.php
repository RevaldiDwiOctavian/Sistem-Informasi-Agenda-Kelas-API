<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class GuruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
 
    	for($i = 1; $i <= 50; $i++){
 
    		DB::table('gurus')->insert([
    			'nama_lengkap' => $faker->name,
    			'nuptk' => $faker->numberBetween(1000,10000),
                'created_at' =>  date("Y-m-d H:i:s"),
    		]);
 
    	}
    }
}