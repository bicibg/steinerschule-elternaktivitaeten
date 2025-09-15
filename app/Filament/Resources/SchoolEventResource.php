<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SchoolEventResource\Pages;
use App\Filament\Resources\SchoolEventResource\RelationManagers;
use App\Models\SchoolEvent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SchoolEventResource extends Resource
{
    protected static ?string $model = SchoolEvent::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'Schulkalender';

    protected static ?string $modelLabel = 'Schulveranstaltung';

    protected static ?string $pluralModelLabel = 'Schulveranstaltungen';

    protected static ?string $navigationGroup = 'Schulverwaltung';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Titel')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label('Beschreibung')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\DatePicker::make('start_date')
                    ->label('Startdatum')
                    ->required()
                    ->native(false)
                    ->displayFormat('d.m.Y'),
                Forms\Components\DatePicker::make('end_date')
                    ->label('Enddatum')
                    ->native(false)
                    ->displayFormat('d.m.Y')
                    ->afterOrEqual('start_date'),
                Forms\Components\TextInput::make('event_time')
                    ->label('Uhrzeit')
                    ->placeholder('z.B. 19:00 Uhr')
                    ->helperText('Nur zur Information, wenn nicht ganztägig')
                    ->maxLength(255),
                Forms\Components\TextInput::make('location')
                    ->label('Ort')
                    ->maxLength(255),
                Forms\Components\Select::make('event_type')
                    ->label('Veranstaltungstyp')
                    ->options([
                        'festival' => 'Fest',
                        'meeting' => 'Treffen',
                        'performance' => 'Aufführung',
                        'holiday' => 'Ferien',
                        'sports' => 'Sport',
                        'excursion' => 'Ausflug',
                        'other' => 'Sonstiges',
                    ])
                    ->default('other'),
                Forms\Components\Toggle::make('all_day')
                    ->label('Ganztägig')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Titel')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Startdatum')
                    ->date('d.m.Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->label('Enddatum')
                    ->date('d.m.Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('location')
                    ->label('Ort')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\BadgeColumn::make('event_type')
                    ->label('Typ')
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'festival' => 'Fest',
                        'meeting' => 'Treffen',
                        'performance' => 'Aufführung',
                        'holiday' => 'Ferien',
                        'sports' => 'Sport',
                        'excursion' => 'Ausflug',
                        default => 'Sonstiges',
                    })
                    ->colors([
                        'danger' => 'festival',
                        'primary' => 'meeting',
                        'secondary' => 'performance',
                        'gray' => 'holiday',
                        'success' => 'sports',
                        'warning' => 'excursion',
                    ]),
                Tables\Columns\IconColumn::make('all_day')
                    ->label('Ganztägig')
                    ->boolean()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Erstellt')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Aktualisiert')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('event_type')
                    ->label('Veranstaltungstyp')
                    ->options([
                        'festival' => 'Fest',
                        'meeting' => 'Treffen',
                        'performance' => 'Aufführung',
                        'holiday' => 'Ferien',
                        'sports' => 'Sport',
                        'excursion' => 'Ausflug',
                        'other' => 'Sonstiges',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('start_date', 'asc');
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
            'index' => Pages\ListSchoolEvents::route('/'),
            'create' => Pages\CreateSchoolEvent::route('/create'),
            'edit' => Pages\EditSchoolEvent::route('/{record}/edit'),
        ];
    }
}
