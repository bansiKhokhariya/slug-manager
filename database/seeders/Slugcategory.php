<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
class Slugcategory extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('slugs_category')->insert([
            'category' => 'All',
            'add_by' => 1,
            'updated_by' => 1,
            'status' => 'A',
            'is_deleted' => 'N',
            'created_at' => date("Y-m-d h:i:s"),
            'updated_at' => date("Y-m-d h:i:s"),
        ]);
        DB::table('slugs_category')->insert([
            'category' => 'Subtitle',
            'add_by' => 1,
            'updated_by' => 1,
            'status' => 'A',
            'is_deleted' => 'N',
            'created_at' => date("Y-m-d h:i:s"),
            'updated_at' => date("Y-m-d h:i:s"),
        ]);
        DB::table('slugs_category')->insert([
            'category' => 'Topic',
            'add_by' => 1,
            'updated_by' => 1,
            'status' => 'A',
            'is_deleted' => 'N',
            'created_at' => date("Y-m-d h:i:s"),
            'updated_at' => date("Y-m-d h:i:s"),
        ]);
        DB::table('slugs_category')->insert([
            'category' => 'Sub-topic',
            'add_by' => 1,
            'updated_by' => 1,
            'status' => 'A',
            'is_deleted' => 'N',
            'created_at' => date("Y-m-d h:i:s"),
            'updated_at' => date("Y-m-d h:i:s"),
        ]);
        DB::table('slugs_category')->insert([
            'category' => 'Reference',
            'add_by' => 1,
            'updated_by' => 1,
            'status' => 'A',
            'is_deleted' => 'N',
            'created_at' => date("Y-m-d h:i:s"),
            'updated_at' => date("Y-m-d h:i:s"),
        ]);
    }
}
