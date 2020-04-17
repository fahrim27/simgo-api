<?php

use Illuminate\Database\Seeder;

class Tahun_spjTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pegawai')->insert([
        	'tahun' => 2019,
        	'created_at' => Carbon::now()->format('Y-m-d H:i:s');
        	'updated_at' => Carbon::now()->format('Y-m-d H:i:s');
        ]);
    }
}
