<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NgReportResource\Pages;
use App\Models\NgReport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Get;
use Illuminate\Support\Facades\DB;

class NgReportResource extends Resource
{
    protected static ?string $model = NgReport::class;

    protected static ?string $navigationIcon  = 'heroicon-o-exclamation-circle';
    protected static ?string $navigationGroup = 'Reports';
    protected static ?string $navigationLabel = 'Part Reports';
    protected static ?int    $navigationSort  = 30;
    protected static ?string $pluralModelLabel  = 'Part Reports';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Supplier & Part')
                    ->schema([
                        Forms\Components\Select::make('supplier_id')
                            ->label('Supplier')
                            ->relationship('supplier', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live() // Menggunakan live() lebih disarankan di Filament v3
                            ->afterStateUpdated(fn ($set) => $set('part_id', null)),

                        Forms\Components\Select::make('part_id')
                            ->label('Part')
                            ->placeholder(fn (Get $get): string => empty($get('supplier_id')) ? 'Pilih supplier terlebih dahulu' : 'Pilih Part')
                            ->options(function (Get $get) {
                                $supplierId = $get('supplier_id');

                                if (! $supplierId) {
                                    return [];
                                }

                                // Ambil data part berdasarkan supplier_id
                                // Pastikan kolom di database adalah 'default_supplier_id' sesuai PartResource Anda
                                return \App\Models\Part::query()
                                    ->where('default_supplier_id', $supplierId) 
                                    ->get()
                                    ->mapWithKeys(function ($part) {
                                        return [$part->id => "{$part->part_no} — {$part->part_name}"];
                                    });
                            })
                            ->searchable()
                            ->required()
                            // Menambahkan key agar dropdown refresh saat supplier_id berubah
                            ->key('part_id_select'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Detail NG')
                    ->schema([
                        Forms\Components\Select::make('ng_category')
                            ->label('Kategori NG')
                            ->options([
                                'DIMENSION'  => 'Dimension',
                                'APPEARANCE' => 'Appearance',
                                'FUNCTION'   => 'Function',
                                'PACKAGING'  => 'Packaging',
                                'OTHER'      => 'Lainnya',
                            ])
                            ->required(),

                        Forms\Components\Textarea::make('ng_detail')
                            ->label('Detail NG')
                            ->required()
                            ->rows(4),
                    ])
                    ->columns(1),

                Forms\Components\Section::make('Foto & Tanggal')
                    ->schema([
                        Forms\Components\FileUpload::make('photos')
                            ->label('Photos')
                            ->multiple()
                            ->image()
                            ->disk('public')
                            ->directory('ng-reports')
                            ->visibility('public')
                            ->preserveFilenames(),

                        Forms\Components\DatePicker::make('input_date')
                            ->label('Tanggal Input')
                            ->default(now())
                            ->required(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Status Report')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'DRAFT'  => 'Draft',
                                'SENT'   => 'Sent',
                                'FAILED' => 'Failed',
                            ])
                            ->required()
                            ->default('DRAFT'),

                        Forms\Components\DateTimePicker::make('email_sent_at')
                            ->label('Email Terkirim Pada')
                            ->disabled()
                            ->helperText('Terisi otomatis ketika email berhasil dikirim.'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginated([10, 25, 50])
            ->defaultPaginationPageOption(10)
            ->striped()
            ->columns([
                Tables\Columns\TextColumn::make('supplier.code')
                    ->label('Kode Supp.')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->limit(10),

                Tables\Columns\TextColumn::make('supplier.name')
                    ->label('Supplier')
                    ->searchable()
                    ->limit(25),

                Tables\Columns\TextColumn::make('part.part_no')
                    ->label('Part No')
                    ->searchable(),

                Tables\Columns\TextColumn::make('part.part_name')
                    ->label('Nama Part')
                    ->toggleable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('ng_category')
                    ->label('Kategori NG')
                    ->badge()
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'DIMENSION'  => 'Dimension',
                            'APPEARANCE' => 'Appearance',
                            'FUNCTION'   => 'Function',
                            'PACKAGING'  => 'Packaging',
                            'OTHER'      => 'Lainnya',
                            default      => $state,
                        };
                    }),
                
                Tables\Columns\TextColumn::make('ng_detail')
                    ->label('Detail NG')
                    ->wrap() 
                    ->extraAttributes(['style' => 'min-width: 250px;']),

                Tables\Columns\TextColumn::make('input_date')
                    ->label('Tgl Input')
                    ->date(),

                Tables\Columns\ImageColumn::make('photos') 
                    ->label('Foto')
                    ->disk('public') 
                    ->circular() 
                    ->stacked()  
                    ->limit(1),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'gray'  => 'DRAFT',
                        'success' => 'SENT',
                        'danger'  => 'FAILED',
                    ]),

                Tables\Columns\TextColumn::make('email_sent_at')
                    ->label('Email Sent At')
                    ->dateTime()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'DRAFT'  => 'Draft',
                        'SENT'   => 'Sent',
                        'FAILED' => 'Failed',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('sendEmail')
                    ->label('Kirim Email')
                    ->icon('heroicon-m-paper-airplane')
                    ->color('success')
                    ->requiresConfirmation() // Biar tidak sengaja tertekan
                    ->action(function (NgReport $record) {
                        // Logika pengiriman email
                        \Illuminate\Support\Facades\Mail::to($record->supplier->email) // Pastikan model Supplier punya kolom 'email'
                            ->send(new \App\Mail\NgReportMail($record));

                        // Update status dan tanggal kirim
                        $record->update([
                            'status' => 'SENT',
                            'email_sent_at' => now(),
                        ]);

                        \Filament\Notifications\Notification::make()
                            ->title('Email berhasil dikirim ke ' . $record->supplier->name)
                            ->success()
                            ->send();
                    }),
                Tables\Actions\ViewAction::make()->label(''),
                Tables\Actions\EditAction::make()->label(''),
                Tables\Actions\DeleteAction::make()->label(''),

                // Nanti di sini kita bisa tambahkan action "Kirim Email"
                // Tables\Actions\Action::make('sendEmail')
                //     ->label('Kirim Email')
                //     ->action(fn (NgReport $record) => ...),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // kalau nanti mau ada relasi lain (misal log email) bisa ditaruh di sini
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListNgReports::route('/'),
            'create' => Pages\CreateNgReport::route('/create'),
            'edit'   => Pages\EditNgReport::route('/{record}/edit'),
        ];
    }
}
