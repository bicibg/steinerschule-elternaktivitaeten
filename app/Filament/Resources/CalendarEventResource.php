<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CalendarEventResource\Pages;
use App\Filament\Resources\CalendarEventResource\RelationManagers;
use App\Models\CalendarEvent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CalendarEventResource extends Resource
{
    public static function canViewAny(): bool
    {
        return auth()->user()?->is_super_admin ?? false;
    }
    protected static ?string $model = CalendarEvent::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationLabel = 'Schulkalender';
    protected static ?string $navigationGroup = 'Schule';
    protected static ?string $modelLabel = 'Termin';
    protected static ?string $pluralModelLabel = 'Termine';
    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Termindetails')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Titel')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('type')
                            ->label('Art')
                            ->options(CalendarEvent::getTypeLabels())
                            ->default('other')
                            ->required(),
                        Forms\Components\DatePicker::make('date')
                            ->label('Datum')
                            ->required()
                            ->displayFormat('d.m.Y'),
                        Forms\Components\Toggle::make('all_day')
                            ->label('Ganztägig')
                            ->reactive()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                if ($state) {
                                    $set('start_time', null);
                                    $set('end_time', null);
                                }
                            }),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TimePicker::make('start_time')
                                    ->label('Startzeit')
                                    ->seconds(false)
                                    ->visible(fn (Forms\Get $get) => !$get('all_day')),
                                Forms\Components\TimePicker::make('end_time')
                                    ->label('Endzeit')
                                    ->seconds(false)
                                    ->visible(fn (Forms\Get $get) => !$get('all_day')),
                            ]),
                        Forms\Components\TextInput::make('location')
                            ->label('Ort')
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->label('Beschreibung')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label('Datum')
                    ->date('d.m.Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Titel')
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('type')
                    ->label('Art')
                    ->colors([
                        'gray' => 'holiday',
                        'primary' => 'parent_evening',
                        'success' => 'festival',
                        'warning' => 'other',
                        'danger' => 'concert',
                    ])
                    ->formatStateUsing(fn (string $state): string => CalendarEvent::getTypeLabels()[$state] ?? $state),
                Tables\Columns\TextColumn::make('formatted_time')
                    ->label('Zeit'),
                Tables\Columns\TextColumn::make('location')
                    ->label('Ort')
                    ->placeholder('-')
                    ->toggleable(),
            ])
            ->defaultSort('date', 'asc')
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
            'index' => Pages\ListCalendarEvents::route('/'),
            'create' => Pages\CreateCalendarEvent::route('/create'),
            'edit' => Pages\EditCalendarEvent::route('/{record}/edit'),
        ];
    }
}
