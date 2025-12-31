<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PartResource\Pages;
use App\Models\Part;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PartResource extends Resource
{
    protected static ?string $model = Part::class;

    protected static ?string $navigationIcon  = 'heroicon-o-cube';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Parts';
    protected static ?int    $navigationSort  = 20;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('part_no')
                    ->label('Part No')
                    ->required()
                    ->maxLength(100)
                    ->unique(ignoreRecord: true),

                Forms\Components\TextInput::make('part_name')
                    ->label('Nama Part')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('default_supplier_id')
                    ->label('Default Supplier')
                    ->relationship(
                        name: 'defaultSupplier',
                        titleAttribute: 'name',
                    )
                    ->searchable()
                    ->preload()
                    ->placeholder('Pilih supplier (opsional)')
                    ->nullable(),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginated([10, 25, 50])
            ->defaultPaginationPageOption(10)
            ->striped()
            ->columns([
                Tables\Columns\TextColumn::make('part_no')
                    ->label('Part No')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('part_name')
                    ->label('Nama Part')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('defaultSupplier.code')
                    ->label('Kode Supplier')
                    ->toggleable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('defaultSupplier.name')
                    ->label('Nama Supplier')
                    ->toggleable()
                    ->limit(30),
            ])
            ->filters([
                // belum perlu filter khusus
            ])
            ->actions([
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
            // nanti kalau mau relasi ke NgReports bisa ditambah di sini
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListParts::route('/'),
            'create' => Pages\CreatePart::route('/create'),
            'edit'   => Pages\EditPart::route('/{record}/edit'),
        ];
    }
}
