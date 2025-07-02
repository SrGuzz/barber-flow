<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(20)->create();
        User::create([
            'name' => 'Albert Luis Pereira de Jesus',
            'email' => 'albertluis123y88@gmail.com',
            'access_level' => 1,
            'phone' => '(31) 998663664',
            'avatar' => 'https://lh3.googleusercontent.com/a/ACg8ocJspK_tnnRSo9yubzaTendCairxfOHL_EA4GoGcEj-O4PODMYm2=s96-c',
            'google_id' => '113898287393626247655',
        ]);
    }
}
