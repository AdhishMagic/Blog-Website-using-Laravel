<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TopContributorsWidget extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 5;

    public function table(Table $table): Table
    {
        return $table
            ->heading('⭐ Top Contributors')
            ->description('Most active users in the last 30 days')
            ->query(
                User::query()
                    ->select('users.*')
                    ->selectRaw('(SELECT COUNT(*) FROM posts WHERE users.id = posts.user_id AND posts.created_at >= ? AND posts.deleted_at IS NULL) as posts_count', [now()->subDays(30)])
                    ->selectRaw('(SELECT COUNT(*) FROM comments WHERE users.id = comments.user_id AND comments.created_at >= ? AND comments.deleted_at IS NULL) as comments_count', [now()->subDays(30)])
                    ->whereRaw('(SELECT COUNT(*) FROM posts WHERE users.id = posts.user_id AND posts.created_at >= ? AND posts.deleted_at IS NULL) > 0 
                        OR (SELECT COUNT(*) FROM comments WHERE users.id = comments.user_id AND comments.created_at >= ? AND comments.deleted_at IS NULL) > 0',
                        [now()->subDays(30), now()->subDays(30)])
                    ->orderByRaw('(SELECT COUNT(*) FROM posts WHERE users.id = posts.user_id AND posts.created_at >= ? AND posts.deleted_at IS NULL) + 
                        (SELECT COUNT(*) FROM comments WHERE users.id = comments.user_id AND comments.created_at >= ? AND comments.deleted_at IS NULL) DESC',
                        [now()->subDays(30), now()->subDays(30)])
            )
            ->defaultPaginationPageOption(5)
            ->paginated([5, 10, 25])
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('posts_count')
                    ->label('Posts (30d)')
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('comments_count')
                    ->label('Comments (30d)')
                    ->sortable()
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('total_activity')
                    ->label('Total Activity')
                    ->state(function (User $record): int {
                        return $record->posts_count + $record->comments_count;
                    })
                    ->badge()
                    ->color('warning'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Joined')
                    ->dateTime('M d, Y')
                    ->sortable(),
            ]);
    }
}
