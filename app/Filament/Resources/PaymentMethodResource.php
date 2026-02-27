<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentMethodResource\Pages;
use App\Models\PaymentMethod;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentMethodResource extends Resource
{
    protected static ?string $model = PaymentMethod::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    
    protected static ?string $navigationLabel = 'Metode Pembayaran';
    
    protected static ?string $modelLabel = 'Metode Pembayaran';
    
    protected static ?string $pluralModelLabel = 'Metode Pembayaran';
    
    protected static ?int $navigationSort = 5;
    
    protected static ?string $navigationGroup = 'Pengaturan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('nama_metode')
                            ->required()
                            ->maxLength(255)
                            ->label('Nama Metode')
                            ->helperText('Contoh: Tunai, Transfer Bank, QRIS, dll'),
                        
                        Forms\Components\Textarea::make('keterangan')
                            ->rows(3)
                            ->label('Keterangan')
                            ->helperText('Informasi detail seperti nomor rekening, cara pembayaran, dll'),
                        
                        Forms\Components\Select::make('status')
                            ->options([
                                'aktif' => 'Aktif',
                                'nonaktif' => 'Non-Aktif',
                            ])
                            ->default('aktif')
                            ->required()
                            ->label('Status'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_metode')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Metode'),
                
                Tables\Columns\TextColumn::make('keterangan')
                    ->limit(50)
                    ->label('Keterangan')
                    ->wrap(),
                
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
                    ->label('Dibuat'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'aktif' => 'Aktif',
                        'nonaktif' => 'Non-Aktif',
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
            'index' => Pages\ListPaymentMethods::route('/'),
            'create' => Pages\CreatePaymentMethod::route('/create'),
            'edit' => Pages\EditPaymentMethod::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->can('view payment methods');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('manage payment methods');
    }

    public static function canEdit($record): bool
    {
        return auth()->user()->can('manage payment methods');
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->can('manage payment methods');
    }
}