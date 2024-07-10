<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\tag;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        tag::create(['name' => 'Tag1']);
        tag::create(['name' => 'Tag2']);
        tag::create(['name' => 'Tag3']);
    }
}
