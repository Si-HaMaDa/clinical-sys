<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'HaMaDa',
            'email' => 'admin@admin.com',
            'password' => 'password',
            'phone' => 11111111,
            'gender' => 'm',
            'birthday' => '2020-11-14',
            'image' => 'image.png'
        ]);
        // $user->attachRole('super_admin');

        factory(User::class, 5)->create();
    }
}
