<?php

namespace App\Filament\Admin\Resources\SupplierProductResource\Pages;

use App\Filament\Admin\Resources\SupplierProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSupplierProduct extends EditRecord
{
    protected static string $resource = SupplierProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
