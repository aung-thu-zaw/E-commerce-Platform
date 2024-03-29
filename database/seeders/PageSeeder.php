<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Page::factory()->create(['title' => 'Privacy And Policy']);
        Page::factory()->create(['title' => 'Terms And Conditions']);
    }
}
