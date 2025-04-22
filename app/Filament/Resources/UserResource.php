<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'usuario';

    protected static ?string $pluralModelLabel = 'usuarios';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('client_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('last_name')
                    ->maxLength(50),
                Forms\Components\TextInput::make('username')
                    ->maxLength(50),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->maxLength(12),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(100),
                Forms\Components\DateTimePicker::make('email_verified_at'),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required()
                    ->maxLength(128),
                Forms\Components\DateTimePicker::make('last_session'),
                Forms\Components\TextInput::make('ip_connect')
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_admin')
                    ->required(),
                Forms\Components\TextInput::make('status')
                    ->numeric()
                    ->default(1),
                Forms\Components\TextInput::make('type')
                    ->required()
                    ->numeric()
                    ->default(3),
                Forms\Components\TextInput::make('user_status')
                    ->required()
                    ->maxLength(1)
                    ->default('P'),
                Forms\Components\TextInput::make('client_status')
                    ->required()
                    ->maxLength(1)
                    ->default('A'),
                Forms\Components\DateTimePicker::make('password_change'),
                Forms\Components\DateTimePicker::make('failed_date'),
                Forms\Components\TextInput::make('failed_count')
                    ->numeric(),
                Forms\Components\Toggle::make('confirmed')
                    ->required(),
                Forms\Components\TextInput::make('confirmation_code')
                    ->numeric(),
                Forms\Components\TextInput::make('country_id')
                    ->numeric(),
                Forms\Components\TextInput::make('state_id')
                    ->numeric(),
                Forms\Components\TextInput::make('city_id')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('client_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('username')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user_status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('client_status')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
