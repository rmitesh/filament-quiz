<?php

namespace App\Filament\Resources\QuizeResource\Pages;

use App\Filament\Resources\QuizeResource;
use App\Models\Question;
use Filament\Forms;
use Filament\Pages;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;

class QuizeQuestions extends Page implements Pages\Contracts\HasFormActions
{
    use InteractsWithRecord, Pages\Concerns\HasFormActions;

    protected static string $resource = QuizeResource::class;

    protected static string $view = 'filament.resources.quize-resource.pages.quize-questions';

    public $quizeQuestionsData;

    public ?string $previousUrl = null;

    public function mount($record)
    {
        $this->record = $this->resolveRecord($record);
        $this->updateQuizeQuestionsForm->fill($this->record->questions?->toArray());

        $this->previousUrl = url()->previous();
    }

    protected function getForms(): array
    {
        return array_merge(parent::getForms(), [
            'updateQuizeQuestionsForm' => $this->makeForm()
                ->schema($this->getUpdateQuizeQuestionsFormSchema())
                ->statePath('quizeQuestionsData'),
        ]);
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
            $this->getCancelFormAction(),
        ];
    }

    protected function hasFullWidthFormActions(): bool
    {
        return false;
    }

    protected function getCancelFormAction(): Action
    {
        return Action::make('cancel')
            ->label(__('filament::resources/pages/edit-record.form.actions.cancel.label'))
            ->url($this->previousUrl ?? static::$resource::getUrl())
            ->color('secondary');
    }

    protected function getSaveFormAction(): Action
    {
        return Action::make('save')
            ->label(__('filament::resources/pages/edit-record.form.actions.save.label'))
            ->submit('save')
            ->keyBindings(['mod+s']);
    }

    public function quizeQuestions()
    {
        Question::updateOrCreate([
            'quize_id' => $this->record->id,
        ], $this->updateQuizeQuestionsForm->getState());
        
        $this->notify('success', 'Questions has been added in the quize');
    }

    public function getUpdatequizeQuestionsFormSchema(): array
    {
        return [
            Forms\Components\Builder::make('questions')
                    ->createItemButtonLabel('Add Questions')
                    ->blocks([
                        Forms\Components\Builder\Block::make('questions')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Question')
                                    ->required(),

                                Forms\Components\TextInput::make('point')
                                    ->numeric()
                                    ->required(),
                                
                                Forms\Components\Repeater::make('answers')
                                    ->schema([
                                        Forms\Components\TextInput::make('option')->required(),
                                        Forms\Components\Checkbox::make('valid_answer')->inline(),
                                    ])
                                    ->defaultItems(2)
                                    ->minItems(2)
                                    ->maxItems(4)
                                    ->grid(2)
                                    ->columnSpan('full'),
                            ])
                            ->columns()
                    ])
                    ->collapsible(),
        ];
    }
}
