<?php

namespace App\Filament\Admin\Resources\SupplierOrderResource\Pages;

use App\Filament\Admin\Resources\SupplierOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSupplierOrder extends EditRecord
{
    protected static string $resource = SupplierOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
