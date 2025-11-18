<?php

namespace App\Filament\Widgets;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Filament\Widgets\Widget;
use Illuminate\Support\Collection;

class RecentActivityWidget extends Widget
{
    protected string $view = 'filament.widgets.recent-activity-widget';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 6;

    public function getActivities(): Collection
    {
        $posts = Post::query()
            ->with('author')
            ->latest()
            ->limit(3)
            ->get()
            ->map(function (Post $post) {
                return [
                    'type' => 'post',
                    'icon' => 'heroicon-o-document-text',
                    'color' => 'primary',
                    'title' => $post->title,
                    'user' => $post->author?->name ?? 'Unknown',
                    'timestamp' => $post->created_at,
                    'description' => 'Created a new post',
                ];
            });

        $comments = Comment::query()
            ->with(['user', 'post'])
            ->latest()
            ->limit(3)
            ->get()
            ->map(function (Comment $comment) {
                return [
                    'type' => 'comment',
                    'icon' => 'heroicon-o-chat-bubble-left',
                    'color' => 'success',
                    'title' => substr($comment->content, 0, 50).(strlen($comment->content) > 50 ? '...' : ''),
                    'user' => $comment->user?->name ?? 'Unknown',
                    'timestamp' => $comment->created_at,
                    'description' => 'Commented on "'.($comment->post?->title ?? 'Unknown post').'"',
                ];
            });

        $users = User::query()
            ->latest()
            ->limit(2)
            ->get()
            ->map(function (User $user) {
                return [
                    'type' => 'user',
                    'icon' => 'heroicon-o-user-plus',
                    'color' => 'warning',
                    'title' => $user->name,
                    'user' => 'System',
                    'timestamp' => $user->created_at,
                    'description' => 'Joined the platform',
                ];
            });

        return $posts
            ->concat($comments)
            ->concat($users)
            ->sortByDesc('timestamp')
            ->take(8)
            ->values();
    }
}
