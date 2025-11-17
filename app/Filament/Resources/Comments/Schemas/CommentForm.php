<?php

namespace App\Filament\Resources\Comments\Schemas;

use App\Models\Post;
use App\Models\User;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class CommentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('post_id')
                    ->label('Post')
                    ->options(Post::query()->pluck('title', 'id'))
                    ->searchable()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $set('author_id', optional(Post::find($state))->user_id);
                    }),
                Select::make('user_id')
                    ->label('User')
                    ->options(User::query()->pluck('name', 'id'))
                    ->searchable()
                    ->default(fn () => Auth::id())
                    ->required(),
                Hidden::make('author_id'),
                Textarea::make('content')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
