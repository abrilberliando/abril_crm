<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers;
use App\Models\Project;
use App\Models\Customer;
use App\Enums\ProjectStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('lead_id')
                    ->relationship('lead', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Calon Customer (Lead)')
                    ->default(request()->get('lead_id'))
                    ->required(),

                Forms\Components\Select::make('products')
                    ->relationship('products', 'name')
                    ->label('Produk/Layanan yang Diajukan')
                    ->multiple()
                    ->preload()
                    ->required(),

                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi Project')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('lead.name')->label('Calon Customer')->searchable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => ProjectStatus::Pending,
                        'success' => ProjectStatus::Approved,
                        'danger' => ProjectStatus::Rejected,
                    ]),
                Tables\Columns\TextColumn::make('approver.name')->label('Approved By'),
                Tables\Columns\TextColumn::make('approved_at')->dateTime()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn() => auth()->user()->hasRole('manager'))
                    ->hidden(fn(Project $record) => $record->status !== ProjectStatus::Pending)
                    ->action(function (Project $record) {
                        $record->status = ProjectStatus::Approved;
                        $record->approved_by = auth()->id();
                        $record->approved_at = now();

                        $customer = Customer::firstOrCreate(
                            ['email' => $record->lead->email],
                            [
                                'name' => $record->lead->name,
                                'phone_number' => $record->lead->phone_number,
                            ]
                        );

                        $record->customer_id = $customer->id;
                        $record->save();

                        Notification::make()
                            ->title('Project disetujui!')
                            ->body("Lead '{$record->lead->name}' sekarang menjadi pelanggan.")
                            ->success()
                            ->send();
                    }),
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
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
