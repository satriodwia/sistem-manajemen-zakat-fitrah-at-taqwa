<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MustahikResource\Pages;
use App\Models\Mustahik;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MustahikResource extends Resource
{
    protected static ?string $model = Mustahik::class;

    protected static ?string $navigationIcon = 'heroicon-o-hand-raised';

    protected static ?string $navigationLabel = 'Data Mustahik';

    protected static ?string $modelLabel = 'Mustahik';

    protected static ?string $pluralModelLabel = 'Mustahik';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pribadi')
                    ->schema([
                        Forms\Components\TextInput::make('nama_lengkap')
                            ->required()
                            ->maxLength(255)
                            ->label('Nama Lengkap'),

                        Forms\Components\TextInput::make('nik')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(16)
                            ->label('NIK')
                            ->numeric()
                            ->helperText('Nomor Induk Kependudukan (16 digit)'),

                        Forms\Components\TextInput::make('no_telepon')
                            ->tel()
                            ->required()
                            ->maxLength(15)
                            ->label('No. Telepon'),
                    ])->columns(2),

                Forms\Components\Section::make('Alamat & Kategori')
                    ->schema([
                        Forms\Components\Textarea::make('alamat')
                            ->required()
                            ->rows(3)
                            ->label('Alamat Lengkap'),

                        Forms\Components\Select::make('kategori')
                            ->options(Mustahik::KATEGORI)
                            ->required()
                            ->label('Kategori (Asnaf)')
                            ->helperText('Golongan penerima zakat sesuai 8 asnaf'),

                        Forms\Components\Textarea::make('keterangan')
                            ->rows(2)
                            ->label('Keterangan')
                            ->helperText('Catatan tambahan mengenai mustahik (opsional)'),

                        Forms\Components\Select::make('status')
                            ->options([
                                'aktif' => 'Aktif',
                                'nonaktif' => 'Non-Aktif',
                            ])
                            ->default('aktif')
                            ->required()
                            ->label('Status'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Lengkap'),

                Tables\Columns\TextColumn::make('nik')
                    ->searchable()
                    ->label('NIK'),

                Tables\Columns\TextColumn::make('no_telepon')
                    ->searchable()
                    ->label('No. Telepon'),

                Tables\Columns\BadgeColumn::make('kategori')
                    ->formatStateUsing(fn (string $state): string => Mustahik::KATEGORI[$state] ?? $state)
                    ->colors([
                        'primary' => 'fakir',
                        'warning' => 'miskin',
                        'success' => 'amil',
                        'info' => 'mualaf',
                        'secondary' => 'riqab',
                        'danger' => 'gharimin',
                        'gray' => 'fisabilillah',
                    ])
                    ->label('Kategori'),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'aktif',
                        'danger' => 'nonaktif',
                    ])
                    ->label('Status'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Terdaftar'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kategori')
                    ->options(Mustahik::KATEGORI),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'aktif' => 'Aktif',
                        'nonaktif' => 'Non-Aktif',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListMustahiks::route('/'),
            'create' => Pages\CreateMustahik::route('/create'),
            'edit' => Pages\EditMustahik::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->can('view mustahik');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('create mustahik');
    }

    public static function canEdit($record): bool
    {
        return auth()->user()->can('edit mustahik');
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->can('delete mustahik');
    }
}