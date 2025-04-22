<?php

namespace App\Filament\Resources;

use Illuminate\Support\Facades\Auth;

use App\Filament\Resources\ClientResource\Pages;
use App\Models\City;
use App\Models\Client;
use App\Models\Country;
use App\Models\Department;
use App\Models\Divition;
use App\Models\State;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Filament\Tables\Actions\Action;

use Filament\Notifications\Notification;

//use App\Filament\Resources\Str;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB; // Importa la clase DB


class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'cliente';

    protected static ?string $pluralModelLabel = 'clientes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('company')
                    ->label('Empresa')
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('last_name')
                    ->label('Apellido')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('ip')
                    ->required()
                    ->maxLength(15),
                Forms\Components\TextInput::make('port')
                    ->required()
                    ->maxLength(6),
                Forms\Components\TextInput::make('server_user')
                    ->label('Usuario del Servidor')
                    ->maxLength(50),
                Forms\Components\TextInput::make('server_pass')
                    ->label('Contraseña del Servidor')
                    ->maxLength(50),
                Forms\Components\FileUpload::make('image')
                    ->label('Logo')
                    ->image()
                    ->required(),
                Forms\Components\Toggle::make('status')
                    ->label(fn ($state): string => $state ? 'Activo' : 'Inactivo')
                    ->required(),                    
                Forms\Components\Select::make('divition_id')
                    ->label('Sucursal')
                    ->relationship('divition', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function (Forms\Set $set, $state) {
                        $set('department_id', null);
                        $departments = Department::where('divition_id', $state)->get()->pluck('name', 'id');
                        $set('department_options', $departments);
                    }),
                Forms\Components\Select::make('department_id')
                    ->label('Departamento')
                    ->options(fn (Forms\Get $get): Collection => Department::where('divition_id', $get('divition_id'))->pluck('name', 'id'))
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('country_id')
                    ->label('País')
                    ->options(Country::all()->pluck('name', 'id'))
                    ->required()
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function (Forms\Set $set, $state) {
                        $set('state_id', null);
                        $set('city_id', null);
                        $states = State::where('country_id', $state)->get()->pluck('name', 'id');
                        $set('state_options', $states);
                        $set('city_options', []);
                    }),
                Forms\Components\Select::make('state_id')
                    ->label('Estado')
                    ->options(fn (Forms\Get $get): Collection => State::where('country_id', $get('country_id'))->pluck('name', 'id'))
                    ->required()
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function (Forms\Set $set, $state) {
                        $set('city_id', null);
                        $cities = City::where('state_id', $state)->get()->pluck('name', 'id');
                        $set('city_options', $cities);
                    }),
                Forms\Components\Select::make('city_id')
                    ->label('Ciudad')
                    ->options(fn (Forms\Get $get): Collection => City::where('state_id', $get('state_id'))->pluck('name', 'id'))
                    ->required()
                    ->searchable()
                    ->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Client::query()->with(['divition', 'department', 'country', 'state', 'city']))
            ->columns([
                Tables\Columns\TextColumn::make('index')
                ->label('nro')
                ->sortable()
                ->rowindex(),
                Tables\Columns\TextColumn::make('company')
                    ->label('Empresa')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->label('Apellido')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ip')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('port')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('server_user')
                    ->label('Usuario Servidor')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('server_pass')
                    ->label('Contraseña Servidor')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\ImageColumn::make('image')
                    ->label('Logo'),
                    BadgeColumn::make('status')
                    ->label('Estatus')
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        0 => 'Inactivo',
                        1 => 'Activo',
                        2 => 'Pendiente',
                        default => 'Desconocido',
                    })
                    ->color(fn (int $state): string => match ($state) {
                        0 => 'danger',
                        1 => 'success',
                        2 => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('division.name')
                    ->label('División')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('department.name')
                    ->label('Departamento')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('country.name')
                    ->label('País')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('state.name')
                    ->label('Estado')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('city.name')
                    ->label('Ciudad')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->deferLoading() // Habilita la carga diferida
            ->paginated(true) // Habilita la paginación
            ->defaultPaginationPageOption(25) // Establece un número de registros por página por defecto
            ->paginationPageOptions([10, 25, 50, 100]) // Opciones de paginación
            ->filters([
                //
            ])
            ->actions([
                Action::make('toggleStatus')
                ->label(fn (Client $record): string => $record->status == 1 ? 'Desactivar' : 'Activar')
                ->icon(fn (Client $record): string => $record->status == 1 ? 'heroicon-o-minus-circle' : 'heroicon-o-plus-circle')
                ->color(fn (Client $record): string => $record->status == 1 ? 'warning' : 'success')
                ->action(function (Client $record) {
                    $record->status = !$record->status;
                    $record->save();
            
                    $recipient = Auth::user();

                    if ($recipient) {

                        Notification::make()
                            ->title($record->status ? 'Cliente Activado' : 'Cliente Desactivado')
                            ->body("El cliente {$record->name} {$record->last_name} ha sido " .
                                ($record->status ? 'activado' : 'desactivado'))
                            ->icon($record->status ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                            ->iconColor($record->status ? 'success' : 'danger')
                            ->sendToDatabase($recipient)
                            ->send();

                            $recipient->notify(
                                Notification::make()
                                    ->title('Saved successfully')
                                    ->toDatabase(),
                            );


                    } else {
                        // Log o manejar el caso en que no hay usuario autenticado
                        \Log::warning('No hay usuario autenticado para enviar la notificación.');
                    }
                       
                }),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),

                
            ])











            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'view' => Pages\ViewClient::route('/{record}'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }
}