<?php

namespace App\Filament\Admin\Resources\SupplierProductResource\Pages;

use App\Filament\Admin\Resources\SupplierProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSupplierProducts extends ListRecords
{
    protected static string $resource = SupplierProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
