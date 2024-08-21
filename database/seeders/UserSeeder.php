<?php

namespace Database\Seeders;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
//        url(Storage::url('users/user_avatar_default.png'))
        $users = [
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'image' => asset('users/user_avatar.png'),
                'email' => 'admin@admin.com',
                'password'=>bcrypt('11112222#'),
                'email_verified_at'  => Carbon::now()->toDateTimeString(),
                'user_role' => 'A',
            ]
        ];
        foreach ($users as $user){
            $user = User::create($user);
            $user->assignRole(Role::all());
        }
    }
}
