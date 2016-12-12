<?php

use Faker\Factory as Faker;
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
        $faker = Faker::create();

        foreach(range(1, 30) as $index) {
            DB::table('users')->insert([
                'name' => $faker->name,
                'email' => $faker->email,
                'password' => bcrypt('secret'),
            ]);
            DB::table('products')->insert([
                'user_id' => $index,
                'brand' => $faker->company,
                'name' => $faker->word,
                'category' => $faker->word,
                'price' => $faker->randomFloat(2, 0, 99999),
            ]);
            DB::table('services')->insert([
                'user_id' => $index,
                'title' => $faker->word,
                'category' => $faker->word,
                'price' => $faker->randomFloat(2, 0, 99999),
            ]);
        }
    }
}
