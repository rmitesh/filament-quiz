<?php

namespace App\Filament\Resources\QuizeResource\Pages;

use Closure;
use App\Filament\Resources\QuizeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;

class ListQuizes extends ListRecords
{
    protected static string $resource = QuizeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
