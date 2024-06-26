<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Resources\OrderResource\RelationManagers\AddressRelationManager;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Set;
use Filament\Forms\Get;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Columns\SelectColumn;
use PhpParser\Node\Stmt\Label;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?int $navigationSort = 6;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Order Information')->schema([
                        TextInput::make('nama_pelanggan')
                        ->label('Nama Pelanggan')
                        ->required(),

                        Select::make('user_id')
                            ->label('Admin')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('payment_method')
                            ->options([
                            'qris' => 'QRIS',
                                'cod' => 'Cash On Delivery'
                            ])->required(),

                        Select::make('payment_status')
                            ->options([
                                'pending' => 'Pending',
                                'bayar' => 'Di Bayar',
                                'gagal' => 'Gagal'
                            ])->default('pending')->required(),

                        Textarea::make('notes')
                            ->columnSpanFull()
                    ])->columns(2),

                    Section::make('Order Item')->schema([
                        Repeater::make('items')
                            ->relationship()->schema([
                                Select::make('product_id')
                                    ->relationship('product', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->distinct()
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->columnSpan(4)
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, Set $set) {
                                        $product = Product::find($state);
                                        $unitAmount = $product ? $product->price : 0;
                                        $set('unit_amount', $unitAmount);
                                    })
                                    ->afterStateUpdated(function ($state, Set $set) {
                                        $product = Product::find($state);
                                        $unitAmount = $product ? $product->price : 0;
                                        $set('total_amount', $unitAmount);
                                    }),

                                TextInput::make('quantity')
                                    ->numeric()
                                    ->required()
                                    ->default(1)
                                    ->minValue(1)
                                    ->columnSpan(2)
                                    ->reactive()
                                    ->afterStateUpdated(function($state, Set $set, Get $get) {
                                        $set('total_amount', $state * $get('unit_amount'));
                                    }),

                                TextInput::make('unit_amount')
                                    ->numeric()
                                    ->required()
                                    ->disabled()->dehydrated()->columnSpan(3)
                                    ->prefix('Rp ')
                                    ->afterStateUpdated(function($state, Set $set, Get $get) {
                                        $set('total_amount', number_format($state, 3, '.', ','));
                                    }),

                                TextInput::make('total_amount')
                                    ->numeric()
                                    ->required()
                                    ->dehydrated()
                                    ->columnSpan(3)
                                    ->prefix('Rp ')
                                    ->afterStateUpdated(function($state, Set $set, Get $get) {
                                        $set('total_amount', number_format($state, 3, '.', ','));
                                    }),
                                
                            ])->columns(12),

                            Placeholder::make('grand_total_placeholder')
                            ->label('Grand Total')
                            ->content(function(Get $get, Set $set) {
                                $total = 0;
                                if (!$repeaters = $get('items')) {
                                    return 'Rp 0';
                                }
                        
                                foreach ($repeaters as $key => $repeater) {
                                    $total += $get("items.{$key}.total_amount");
                                }
                        
                                $set('grand_total', $total);
                        
                                return 'Rp ' . number_format($total, 3, '.', ',');
                            }),
                        Hidden::make('grand_total')->default(0)

                    ])

                ])->columnSpanFull()
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_pelanggan')->label('Nama Pelanggan')->sortable()->searchable(),
                TextColumn::make('user.name')->label('Admin')->sortable()->searchable(),
                TextColumn::make('grand_total')->label('Total')->numeric()->sortable()->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.') . ',000'),
                TextColumn::make('payment_method')->label('Metode Pembayaran')->sortable()->searchable(),
                TextColumn::make('payment_status')->label('Status Pembayaran')->sortable()->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()->sortable()->toggleable(isToggledHiddenByDefault:true),

                TextColumn::make('updated_at')
                    ->dateTime()->sortable()->toggleable(isToggledHiddenByDefault:true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
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
            AddressRelationManager::class
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
         return static::getModel()::count() > 10 ? 'success' : 'danger';
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}