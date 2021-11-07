<?php

namespace Database\Seeders;

use App\Models\User;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class SystemAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            User::create([
                'name' => 'System Admin',
                'email' => 'admin@system.com',
                'password' => Hash::make('12345678'),
                'user_type' => 'admin'
            ]);
        } catch (Exception $ex) {
            Log::debug($ex->getMessage());
        }
    }
}
