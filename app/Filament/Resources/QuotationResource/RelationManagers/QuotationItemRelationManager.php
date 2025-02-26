<?php

namespace App\Filament\Resources\QuotationResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\CreateAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use App\Models\Product;
use App\Models\QuotationItem;

class QuotationItemRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

        public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Select::make('product_id')
                ->label('Product')
                ->options(Product::pluck('name', 'id'))
                ->required()
                ->searchable()
                ->reactive()
                ->afterStateUpdated(fn ($state, callable $set) =>
                    $set('price', Product::find($state)?->price ?? 0)
                ),

            TextInput::make('color')
                ->label('Color')
                ->maxLength(50)
                ->nullable(),

            TextInput::make('quantity')
                ->label('Quantity')
                ->numeric()
                ->minValue(1)
                ->required()
                ->reactive()
                ->afterStateUpdated(fn ($state, callable $set, $get) =>
                    $set('subtotal', $state * $get('price'))
                ),

            TextInput::make('price')
                ->label('Price')
                ->numeric()
                ->disabled() // Price should not be manually editable
                ->dehydrated(), // 👈 Ensures it is still sent when saving

            TextInput::make('subtotal')
                ->label('Subtotal')
                ->numeric()
                ->disabled() // Prevents user editing, but now it updates automatically
                ->dehydrated(), // 👈 Ensures it is still sent when saving
        ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            TextColumn::make('product.name')
                ->label('Product')
                ->sortable()
                ->searchable(),

            TextColumn::make('color')
                ->label('Color'),

            TextColumn::make('quantity')
                ->label('Quantity')
                ->sortable(),

            TextColumn::make('price')
                ->label('Price')
                ->money('USD'),

            TextColumn::make('subtotal')
                ->label('Subtotal')
                ->money('USD'),
        ])->actions([
            EditAction::make(),
            DeleteAction::make(),
        ])
        ->headerActions([
            Tables\Actions\CreateAction::make(), // 👈 This forces Filament to show an Add button
        ]);
    }
}
