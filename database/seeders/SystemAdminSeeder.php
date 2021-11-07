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
            $data = [
                [
                    'name' => 'System Admin',
                    'email' => 'admin@example.com',
                    'password' => Hash::make('password'),
                    'user_type' => 'admin'
                ],
                [
                    'name' => 'System Buyer',
                    'email' => 'user@example.com',
                    'password' => Hash::make('password'),
                    'user_type' => 'user'
                ],
            ];
            User::insert($data);
        } catch (Exception $ex) {
            Log::debug($ex->getMessage());
        }
    }
}
