<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\State;

class StateSeeder extends Seeder
{
    public function run()
    {
        $model = new State();
        // 2. Ruta al archivo JSON
        $jsonPath = __DIR__ . '/data/states.json';
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
        
        // Opción 1: Si los estados están directamente en el array principal
        if (isset($data[0]['id']) && isset($data[0]['name'])) {
            $estadosData = $data;
            echo "• Estructura detectada: Array directo de estados\n";
        }
        // Opción 2: Si están dentro de una propiedad 'data'
        elseif (isset($data[0]['data'])) {
            $estadosData = $data[0]['data'];
            echo "• Estructura detectada: data[0]['data']\n";
        }
        // Opción 3: Si es un objeto con propiedad 'data'
        elseif (isset($data['data'])) {
            $estadosData = $data['data'];
            echo "• Estructura detectada: data['data']\n";
        }
        else {
            echo "ℹ Estructura del JSON:\n";
            print_r($data);
            die("✗ ERROR: No se pudo determinar la estructura del JSON\n");
        }

        echo "• Encontrados " . count($estadosData) . " estados en el JSON.\n";

        // 5. Insertar datos con verificación
        $insertados = 0;
        foreach ($estadosData as $estado) {
            try {
                // Verificar si el país ya existe
//                $existe = $model->where('code', $estado['code']);
                $existe = $model->where('id', $estado['id'])->first();
                
                if ($existe) {
                    echo "→ Estado {$estado['id']} ya existe, omitiendo...\n";
                    continue;
                }

                $result = $model->insert([
                    'name' => $estado['name'],
                    'country_id' => $estado['country_id'],
                    'created_at' => $estado['created_at'] ?? date('Y-m-d H:i:s'),
                    'updated_at' => $estado['updated_at'] ?? date('Y-m-d H:i:s')
                ]);
                
                if ($result === false) {
                    echo "✗ Error insertando {$estado['id']}: " . implode(', ', $model->errors()) . "\n";
                } else {
                    $insertados++;
                    echo "✓ Insertado {$estado['id']}: {$estado['name']}\n";
                }
            } catch (\Exception $e) {
                echo "✗ Excepción con {$estado['id']}: " . $e->getMessage() . "\n";
            }
        }

        echo "\nRESULTADO FINAL:\n";
        echo "────────────────\n";
        echo "• Estados en JSON: " . count($estadosData) . "\n";
        echo "• Estados insertados: $insertados\n";

    }
}