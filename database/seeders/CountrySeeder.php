<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Country;

class CountrySeeder extends Seeder
{
    public function run()
    {
        $model = new Country();
        // 2. Ruta al archivo JSON
        $jsonPath = __DIR__ . '/data/countries.json';
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
        
        // Opción 1: Si los países están directamente en el array principal
        if (isset($data[0]['id']) && isset($data[0]['name'])) {
            $paisesData = $data;
            echo "• Estructura detectada: Array directo de países\n";
        }
        // Opción 2: Si están dentro de una propiedad 'data'
        elseif (isset($data[0]['data'])) {
            $paisesData = $data[0]['data'];
            echo "• Estructura detectada: data[0]['data']\n";
        }
        // Opción 3: Si es un objeto con propiedad 'data'
        elseif (isset($data['data'])) {
            $paisesData = $data['data'];
            echo "• Estructura detectada: data['data']\n";
        }
        else {
            echo "ℹ Estructura del JSON:\n";
            print_r($data);
            die("✗ ERROR: No se pudo determinar la estructura del JSON\n");
        }

        echo "• Encontrados " . count($paisesData) . " países en el JSON.\n";

        // 5. Insertar datos con verificación
        $insertados = 0;
        foreach ($paisesData as $pais) {
            try {
                // Verificar si el país ya existe
//                $existe = $model->where('code', $pais['code']);
                $existe = $model->where('code', $pais['code'])->first();
                
                if ($existe) {
                    echo "→ País {$pais['code']} ya existe, omitiendo...\n";
                    continue;
                }

                $result = $model->insert([
                    'name' => $pais['name'],
                    'code' => $pais['code'],
                    'created_at' => $pais['created_at'] ?? date('Y-m-d H:i:s'),
                    'updated_at' => $pais['updated_at'] ?? date('Y-m-d H:i:s')
                ]);
                
                if ($result === false) {
                    echo "✗ Error insertando {$pais['code']}: " . implode(', ', $model->errors()) . "\n";
                } else {
                    $insertados++;
                    echo "✓ Insertado {$pais['code']}: {$pais['name']}\n";
                }
            } catch (\Exception $e) {
                echo "✗ Excepción con {$pais['code']}: " . $e->getMessage() . "\n";
            }
        }

        echo "\nRESULTADO FINAL:\n";
        echo "────────────────\n";
        echo "• Países en JSON: " . count($paisesData) . "\n";
        echo "• Países insertados: $insertados\n";

    }
}