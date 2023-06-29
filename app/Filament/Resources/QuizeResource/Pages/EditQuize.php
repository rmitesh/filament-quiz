<?php

namespace App\Filament\Resources\QuizeResource\Pages;

use App\Filament\Resources\QuizeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQuize extends EditRecord
{
    protected static string $resource = QuizeResource::class;

    public ?string $previousUrl = null;

    protected function getActions(): array
    {
        return [
            Actions\Action::make('questions')
                ->label('Add Questions')
                ->url(fn () => route('filament.resources.quizes.questions', ['record' => $this->record])),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getCancelFormAction(): Actions\Action
    {
        return Actions\Action::make('cancel')
            ->label(__('filament::resources/pages/edit-record.form.actions.cancel.label'))
            ->url(static::$resource::getUrl())
            ->color('secondary');
    }
}
