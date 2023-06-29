<?php

namespace App\Filament\Resources\QuizeResource\Pages;

use App\Filament\Resources\QuizeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CreateQuize extends CreateRecord
{
    protected static string $resource = QuizeResource::class;

    protected static bool $canCreateAnother = false;

    protected function handleRecordCreation(array $data): Model
    {
        $data['slug'] = Str::slug($data['title']);
        $data['created_by'] = auth()->id();
        
        $record = static::getModel()::create($data);
        
        return $record;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('questions', ['record' => $this->record]);
    }
}
