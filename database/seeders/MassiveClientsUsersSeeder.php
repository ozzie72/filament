<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Client;
use App\Models\User;

class MassiveClientsUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
//        $totalRecords = 1000000000;
        $totalRecords = 1000;
//        $chunkSize = 5000; // Procesar en lotes para mejor rendimiento
        $chunkSize = 100; // Procesar en lotes para mejor rendimiento

        $this->command->info("Generando {$totalRecords} clientes con sus usuarios...");

        // Desactivar logs de consultas para mejor rendimiento
        DB::disableQueryLog();

        $bar = $this->command->getOutput()->createProgressBar($totalRecords);
        $bar->start();

        // Procesar en chunks
        for ($i = 0; $i < $totalRecords; $i += $chunkSize) {
            $currentChunkSize = min($chunkSize, $totalRecords - $i);
            $clients = [];
            $users = [];

            // Generar datos para el chunk actual
            for ($j = 0; $j < $currentChunkSize; $j++) {
                $company = 'Company ' . Str::random(5) . ' ' . ($i + $j + 1);
                $firstName = 'Name' . ($i + $j + 1);
                $lastName = 'LastName' . ($i + $j + 1);
                $username = 'user' . ($i + $j + 1);
                $email = $username . '@example.com';

                // Datos del cliente
                $clientId = $i + $j + 1;
                $clients[] = [
                    'company' => $company,
                    'name' => $firstName,
                    'last_name' => $lastName,
                    'ip' => '192.168.' . rand(0, 255) . '.' . rand(1, 254),
                    'image' => 'default.jpg',
                    'port' => rand(8000, 9000),
                    'server_user' => 'server_user_' . Str::random(4),
                    'server_pass' => 'pass_' . Str::random(8),
                    'status' => rand(0, 1),
                    'divition_id' => 1,
                    'department_id' => 1,
                    'country_id' => 238,
                    'state_id' => 4,
                    'city_id' => 52,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // Datos del usuario asociado
                $users[] = [
                    'name' => $firstName,
                    'last_name' => $lastName,
                    'username' => $username,
                    'email' => $email,
                    'phone' => '555-' . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT),
                    'password' => Hash::make('password'),
                    'country_id' => rand(1, 5),
                    'state_id' => 4,
                    'city_id' => 52,
                    'client_status' => rand(0, 1),
                    'user_status' => rand(0, 1),
                    'client_id' => $clientId,
                    'password_change' => now(),
                    'confirmed' => rand(0, 1),
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $bar->advance();
            }

            // Insertar los clientes del chunk actual
            DB::table('clients')->insert($clients);

            // Insertar los usuarios del chunk actual
            DB::table('users')->insert($users);

            // Limpiar los arrays para el próximo chunk
            $clients = [];
            $users = [];
        }

        $bar->finish();
        $this->command->newLine();
        $this->command->info('Se han generado 1,000,000 clientes con sus usuarios correspondientes.');
    }
}