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
        $gender = $faker->randomElement(['Laki-laki', 'Perempuan']);
 
    	for($i = 1; $i <= 50; $i++){
 
    		DB::table('gurus')->insert([
    			'nuptk' => $faker->numberBetween(1000,10000),
    			'nama_lengkap' => $faker->name,
    			'jenis_kelamin' => $gender,
                'created_at' =>  date("Y-m-d H:i:s"),
    		]);
 
    	}
    }
}
