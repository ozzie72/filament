<?php

namespace App\Filament\Resources;

use App\Models\Log;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Illuminate\Support\Facades\Auth;

abstract class BaseResource extends Resource
{
    protected static function logTableAction(string $operation, $record, ?string $customDetails = null): void
    {
        if (Auth::check()) {
            $modelName = class_basename($record);
            $details = $customDetails ?? "Acción {$operation} en {$modelName}: " . ($record->name ?? $record->id);

            Log::create([
                'user_id' => Auth::id(),
                'operation' => $operation,
                'ip' => request()->ip(),
                'method' => request()->method(),
                'url' => request()->fullUrl(),
                'type' => strtolower($modelName),
                'model_id' => $record->id,
                'model_type' => get_class($record),
                'details' => $details,
            ]);
        }
    }

    public static function table(Table $table): Table
    {
        return $table
            ->actions([
                ViewAction::make()
                    ->before(function ($action, $record) {
                        static::logTableAction('view', $record);
                    }),
                EditAction::make()
                    ->before(function ($action, $record) {
                        static::logTableAction('edit', $record);
                    }),
                DeleteAction::make()
                    ->before(function ($action, $record) {
                        static::logTableAction('delete', $record);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function ($action, $records) {
                            $modelName = class_basename(static::getModel());
                            $details = "Eliminación masiva de {$modelName}: " . $records->pluck('id')->implode(', ');

                            Log::create([
                                'user_id' => Auth::id(),
                                'operation' => 'bulk_delete',
                                'ip' => request()->ip(),
                                'method' => request()->method(),
                                'url' => request()->fullUrl(),
                                'type' => strtolower($modelName),
                                'details' => $details,
                            ]);
                        }),
                ]),
            ]);
    }
}