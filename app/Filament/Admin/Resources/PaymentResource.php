<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PaymentResource\Pages;
use App\Filament\Admin\Resources\PaymentResource\RelationManagers;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('stripe_id')
                    ->label('Stripe ID')
                    ->readOnly(),
                Forms\Components\TextInput::make('amount')
                    ->label('Amount')
                    ->readOnly(),
                Forms\Components\TextInput::make('currency')
                    ->label('Currency')
                    ->readOnly(),
                Forms\Components\TextInput::make('status')
                    ->label('Status')
                    ->readOnly(),
                Forms\Components\Textarea::make('payload')
                    ->label('Full Payload')
                    ->columnSpanFull()
                    ->rows(10)
                    ->readOnly()
                    ->afterStateHydrated(function (Forms\Components\Textarea $component, $state) {
                        if (is_array($state)) {
                            $component->state(json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                        }
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('stripe_id')
                    ->label('Stripe ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('currency'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'success' => 'success',
                        'failed' => 'danger',
                        'pending' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePayments::route('/'),
        ];
    }
}
