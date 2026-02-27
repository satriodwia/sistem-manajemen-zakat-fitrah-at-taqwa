<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MuzakkiResource\Pages;
use App\Models\Muzakki;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MuzakkiResource extends Resource
{
    protected static ?string $model = Muzakki::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    protected static ?string $navigationLabel = 'Data Muzakki';
    
    protected static ?string $modelLabel = 'Muzakki';
    
    protected static ?string $pluralModelLabel = 'Muzakki';
    
    protected static ?int $navigationSort = 1;

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
                        
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->maxLength(255)
                            ->label('Email'),
                    ])->columns(2),
                
                Forms\Components\Section::make('Alamat & Detail')
                    ->schema([
                        Forms\Components\Textarea::make('alamat')
                            ->required()
                            ->rows(3)
                            ->label('Alamat Lengkap'),
                        
                        Forms\Components\TextInput::make('jumlah_tanggungan')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->label('Jumlah Tanggungan')
                            ->helperText('Jumlah jiwa yang menjadi tanggungan (termasuk diri sendiri)'),
                        
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
                
                Tables\Columns\TextColumn::make('jumlah_tanggungan')
                    ->numeric()
                    ->sortable()
                    ->label('Tanggungan')
                    ->suffix(' jiwa'),
                
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
            'index' => Pages\ListMuzakkis::route('/'),
            'create' => Pages\CreateMuzakki::route('/create'),
            'edit' => Pages\EditMuzakki::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->can('view muzakki');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('create muzakki');
    }

    public static function canEdit($record): bool
    {
        return auth()->user()->can('edit muzakki');
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->can('delete muzakki');
    }
}