<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\City;

class CitieSeeder extends Seeder
{
    public function run()
    {
        $model = new City();
        // 2. Ruta al archivo JSON
        $jsonPath = __DIR__ . '/data/cities.json';
        echo "• Buscando archivo JSON en: $jsonPath\n";

        if (!file_exists($jsonPath)) {
            die("✗ ERROR: El archivo JSON no existe en la ruta especificada.\n");
        }

        // 3. Leer y decodificar el JSON
        $json = file_get_contents($jsonPath);
        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            die("✗ ERROR al decodificar JSON: " . json_last_error_msg() . "\n");
        }

        // 4. ANALIZAR LA ESTRUCTURA REAL DEL JSON
        echo "ℹ Analizando estructura del JSON...\n";
        
        // Opción 1: Si las cuidades están directamente en el array principal
        if (isset($data[0]['id']) && isset($data[0]['name'])) {
            $citiesData = $data;
            echo "• Estructura detectada: Array directo de países\n";
        }
        // Opción 2: Si están dentro de una propiedad 'data'
        elseif (isset($data[0]['data'])) {
            $citiesData = $data[0]['data'];
            echo "• Estructura detectada: data[0]['data']\n";
        }
        // Opción 3: Si es un objeto con propiedad 'data'
        elseif (isset($data['data'])) {
            $citiesData = $data['data'];
            echo "• Estructura detectada: data['data']\n";
        }
        else {
            echo "ℹ Estructura del JSON:\n";
            print_r($data);
            die("✗ ERROR: No se pudo determinar la estructura del JSON\n");
        }

        echo "• Encontrados " . count($citiesData) . " estados en el JSON.\n";

        // 5. Insertar datos con verificación
        $insertados = 0;
        foreach ($citiesData as $ciudad) {
            try {
                // Verificar si el país ya existe
//                $existe = $model->where('code', $ciudad['code']);
                $existe = $model->where('id', $ciudad['id'])->first();
                
                if ($existe) {
                    echo "→ Ciudad {$ciudad['id']} ya existe, omitiendo...\n";
                    continue;
                }

                $result = $model->insert([
                    'name' => $ciudad['name'],
                    'state_id' => $ciudad['state_id'],
                    'created_at' => $ciudad['created_at'] ?? date('Y-m-d H:i:s'),
                    'updated_at' => $ciudad['updated_at'] ?? date('Y-m-d H:i:s')
                ]);
                
                if ($result === false) {
                    echo "✗ Error insertando {$ciudad['id']}: " . implode(', ', $model->errors()) . "\n";
                } else {
                    $insertados++;
                    echo "✓ Insertado {$ciudad['id']}: {$ciudad['name']}\n";
                }
            } catch (\Exception $e) {
                echo "✗ Excepción con {$ciudad['id']}: " . $e->getMessage() . "\n";
            }
        }

        echo "\nRESULTADO FINAL:\n";
        echo "────────────────\n";
        echo "• Ciudades en JSON: " . count($citiesData) . "\n";
        echo "• Ciudades insertados: $insertados\n";

    }
}