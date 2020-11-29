<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $user = User::create([
            'name' => 'Hamada',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
            'phone' => '0102020',
            'address' => 'haram',
            'gender' => 'm',
            'age' => '18',
            'image' => 'nnn',
        ]);

        $user->attachRole('superadministrator');
    }
}
