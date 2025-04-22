<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin'), 
            'client_id' => 1, // Asignar client_id = 1
            'email_verified_at' => now(),
            'confirmed' => 1,

        ]);

        // Asignar el rol de administrador (si tienes roles definidos)
        $adminRole = Role::where('name', 'admin')->first(); // Verifica que el role admin exista.
        if($adminRole){
            $user->assignRole($adminRole);
        }
    }
}
