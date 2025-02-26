<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuotationResource\Pages;
use App\Filament\Resources\QuotationResource\RelationManagers;
use App\Models\Quotation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Grid;

use App\Filament\Resources\QuotationResource\RelationManagers\QuotationItemRelationManager;

class QuotationResource extends Resource
{
    protected static ?string $model = Quotation::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            Forms\Components\Select::make('user_id')
                ->relationship('user', 'name')
                ->nullable()
                ->label('User (if logged in)'),
            Forms\Components\TextInput::make('guest_name')->label('Guest Name')->nullable(),
            Forms\Components\TextInput::make('guest_email')->label('Guest Email')->email()->nullable(),
            Forms\Components\TextInput::make('guest_phone')->label('Guest Phone')->tel()->nullable(),
            Forms\Components\Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'approved' => 'Approved',
                    'rejected' => 'Rejected',
                ])
                ->required(),
            Forms\Components\Select::make('payment_status')
                ->options([
                    'pending' => 'Pending',
                    'paid' => 'Paid',
                ])
                ->required(),
        ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Quotation Details')->schema([
                    Grid::make(4) // Set number of columns (adjust as needed)
                        ->schema([
                            TextEntry::make('user.name')
                                ->label('User')
                                ->hidden(fn ($record) => !$record->user_id),

                            TextEntry::make('guest_name')
                                ->label('Guest Name')
                                ->hidden(fn ($record) => $record->user_id),

                            TextEntry::make('guest_email')
                                ->label('Guest Email')
                                ->hidden(fn ($record) => $record->user_id),

                            TextEntry::make('guest_phone')
                                ->label('Guest Phone')
                                ->hidden(fn ($record) => $record->user_id),
                        ]),
                    TextEntry::make('status')->label('Status')->badge()
                        ->colors([
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    ]),
                    TextEntry::make('payment_status')->label('Payment Status')->badge()
                    ->colors([
                        'pending' => 'warning',
                        'paid' => 'success',
                    ]),
                ]),


                Section::make('Quotation Items')->schema([
                    RepeatableEntry::make('items')->schema([
                        TextEntry::make('product.name')->label('Product'),
                        TextEntry::make('color')->label('Color'),
                        TextEntry::make('quantity')->label('Quantity'),
                        TextEntry::make('price')->label('Price'),
                        TextEntry::make('subtotal')->label('Subtotal'),
                    ]),
                ]),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('id')->label('ID')->sortable(),
            TextColumn::make('user.name')->label('User')->sortable()->searchable(),
            TextColumn::make('guest_name')->label('Guest Name')->sortable()->searchable(),
            TextColumn::make('guest_email')->label('Guest Email')->searchable(),
            TextColumn::make('guest_phone')->label('Guest Phone')->searchable(),
            TextColumn::make('status')->label('Status')->sortable()->badge()
                ->colors([
                    'pending' => 'warning',
                    'approved' => 'success',
                    'rejected' => 'danger',
                ]),
            TextColumn::make('payment_status')->label('Payment Status')->sortable()->badge()
                ->colors([
                    'pending' => 'warning',
                    'paid' => 'success',
                ]),
            TextColumn::make('created_at')->label('Created At')->sortable()->dateTime(),
        ])
        ->filters([
            //
        ])
        ->actions([
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
    }

    public static function getRelations(): array
    {
        return [
        QuotationItemRelationManager::class,
    ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuotations::route('/'),
            'create' => Pages\CreateQuotation::route('/create'),
            'edit' => Pages\EditQuotation::route('/{record}/edit'),
        ];
    }
}
