<?php

namespace Database\Seeders;

use App\Models\SeoSetting;
use Illuminate\Database\Seeder;

class SeoSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SeoSetting::create([
            'meta_title' => 'Global E-commerce Website',
            'meta_author' => 'Aung Thu Zaw',
            'meta_keyword' => 'Online Shopping,Global Online Shopping, Shipping, E-commerce Website',
            'meta_description' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
        ]);
    }
}
