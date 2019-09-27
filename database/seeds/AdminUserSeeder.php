<?php

use Illuminate\Database\Seeder;
use App\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class)->create([
            'id' => 1,
            'name' => 'John Doe',
            'email' => 'john@doe.com',
            'is_admin' => 1
        ]);
    }
}
