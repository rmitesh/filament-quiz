<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuizeResource\Pages;
use App\Filament\Resources\QuizeResource\RelationManagers;
use App\Models\Quize;
use Filament\Forms;
use Filament\Pages\Actions\EditAction;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class QuizeResource extends Resource
{
    protected static ?string $model = Quize::class;

    protected static ?string $navigationIcon = 'heroicon-o-puzzle';

    protected static ?string $navigationGroup = 'Quize';

    public static function getEloquentQuery(): Builder
    {
        return static::getModel()::query()->whereBelongsTo(auth()->user())->latest();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->placeholder('Title')
                                    ->maxLength(100)
                                    ->autofocus()
                                    ->columnSpan('full')
                                    ->required(),

                                Forms\Components\TextInput::make('number_of_participants')
                                    ->placeholder('Number of Participants')
                                    ->numeric()
                                    ->required(),
                            ])
                            ->columnSpan(2)
                            ->columns(2),

                        Forms\Components\Grid::make(1)
                               ->schema([
                                   Forms\Components\Card::make()
                                       ->schema([
                                            Forms\Components\TextInput::make('key')
                                                ->label('Key')
                                                ->extraInputAttributes(['readonly' => 'readonly'])
                                                ->default(fn () => Str::random(12)),

                                            Forms\Components\Checkbox::make('visibility')
                                                ->label('Make Private')
                                                ->reactive(),
                                            
                                            Forms\Components\TextInput::make('password')
                                                ->label('Password')
                                                ->placeholder('Quize Password')
                                                ->maxLength(20)
                                                ->password()
                                                ->required()
                                                ->visible(function (callable $get): bool {
                                                    return $get('visibility') == '1';
                                                }),
                                       ]),
                               ])
                               ->columnSpan(1),
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->description(function (Model $record) {
                        return "Key: {$record->key}";
                    }),

                Tables\Columns\TextColumn::make('questions.total_questions')
                    ->label('Total Questions'),

                Tables\Columns\BadgeColumn::make('visibility')
                    ->colors([
                        'success',
                        'primary' => 1,
                    ])
                    ->enum([
                        0 => 'Public',
                        1 => 'Private',
                    ]),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'secondary',
                        'success' => 1,
                    ])
                    ->enum([
                        0 => 'Not Started',
                        1 => 'Running',
                    ]),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('dS F, Y'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('start_game')
                    ->icon('heroicon-o-play')
                    ->color('success')
                    ->visible(fn (Model $record) => !$record->status)
                    ->action(fn (Model $record) => $record->update(['status' => true]))
                    ->requiresConfirmation()
                    ->modalButton('Start')
                    ->modalSubheading('Are you sure you want to start the Quize?'),

                Tables\Actions\Action::make('stop_game')
                    ->icon('heroicon-o-pause')
                    ->color('danger')
                    ->visible(fn (Model $record) => $record->status)
                    ->action(fn (Model $record) => $record->update(['status' => false]))
                    ->requiresConfirmation()
                    ->modalButton('Stop')
                    ->modalSubheading('Are you sure you want to stop the Quize?'),

                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('Questions')
                        ->color('success')
                        ->icon('heroicon-o-question-mark-circle')
                        ->url(fn (Model $record): string => static::getUrl('questions', ['record' => $record])),

                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListQuizes::route('/'),
            'questions' => Pages\QuizeQuestions::route('{record}/questions'),
            'create' => Pages\CreateQuize::route('/create'),
            'edit' => Pages\EditQuize::route('/{record}/edit'),
        ];
    }    
}
