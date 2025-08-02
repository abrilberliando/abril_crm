<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\LeadResource;
use App\Models\Lead;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class MyRecentLeadsTable extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Lead::query()
                    ->where('sales_id', auth()->id())
                    ->where('status', 'new')
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama'),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Masuk')
                    ->since(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Lihat Detail')
                    ->url(fn (Lead $record): string => LeadResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
