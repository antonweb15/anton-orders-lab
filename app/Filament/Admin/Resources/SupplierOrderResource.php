<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SupplierOrderResource\Pages;
use App\Filament\Admin\Resources\SupplierOrderResource\RelationManagers;
use App\Models\SupplierOrder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

/**
 * This Resource class defines the UI and behavior for Supplier Orders.
 * Filament converts this into a Livewire component for real-time interaction.
 */
class SupplierOrderResource extends Resource
{
    protected static ?string $model = SupplierOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    /**
     * Defines the form UI.
     * Powered by Livewire: provides instant validation and dynamic fields.
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('external_id')
                    ->numeric(),
                Forms\Components\TextInput::make('customer_name')
                    ->required(),
                Forms\Components\TextInput::make('product')
                    ->required(),
                Forms\Components\TextInput::make('quantity')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->prefix('$')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'new' => 'New',
                        'received' => 'Received',
                        'processed' => 'Processed',
                    ])
                    ->default('new')
                    ->required(),
            ]);
    }

    /**
     * Defines the table UI.
     * Powered by Livewire: sorting, searching and pagination work without page refresh.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('external_id')->sortable(),
                Tables\Columns\TextColumn::make('customer_name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('product')->searchable(),
                Tables\Columns\TextColumn::make('quantity')->sortable(),
                Tables\Columns\TextColumn::make('price')->money()->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'gray' => 'new',
                        'info' => 'received',
                        'success' => 'processed',
                    ]),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListSupplierOrders::route('/'),
            'create' => Pages\CreateSupplierOrder::route('/create'),
            'edit' => Pages\EditSupplierOrder::route('/{record}/edit'),
        ];
    }
}
