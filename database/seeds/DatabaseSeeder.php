<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('projects')->insert([
    		'name' => 'Proyecto React', 'description' => 'This project runs with React', 'status'=>'enabled']);
    }
}
