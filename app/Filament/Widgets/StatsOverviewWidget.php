<?php

namespace App\Filament\Widgets;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    protected function getColumns(): int
    {
        return 3; // Display 3 stats per row on desktop
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Total Posts', Post::count())
                ->description($this->getPostsTrend())
                ->descriptionIcon($this->getPostsTrendIcon())
                ->color($this->getPostsTrendColor())
                ->chart($this->getPostsChartData()),

            Stat::make('Total Users', User::count())
                ->description($this->getUsersTrend())
                ->descriptionIcon($this->getUsersTrendIcon())
                ->color($this->getUsersTrendColor())
                ->chart($this->getUsersChartData()),

            Stat::make('Total Comments', Comment::count())
                ->description($this->getCommentsTrend())
                ->descriptionIcon($this->getCommentsTrendIcon())
                ->color($this->getCommentsTrendColor())
                ->chart($this->getCommentsChartData()),

            Stat::make('Active Users (30d)', $this->getActiveUsers())
                ->description('Users with activity in last 30 days')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),

            Stat::make('Avg Comments/Post', $this->getAvgCommentsPerPost())
                ->description('Average engagement per post')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('info'),

            Stat::make('This Week', $this->getThisWeekActivity())
                ->description('New posts + comments this week')
                ->descriptionIcon('heroicon-m-sparkles')
                ->color('warning'),
        ];
    }

    protected function getPostsTrend(): string
    {
        $lastMonth = Post::whereBetween('created_at', [now()->subMonth(), now()])->count();
        $previousMonth = Post::whereBetween('created_at', [now()->subMonths(2), now()->subMonth()])->count();

        if ($previousMonth == 0) {
            return $lastMonth > 0 ? '+100%' : 'No change';
        }

        $change = (($lastMonth - $previousMonth) / $previousMonth) * 100;

        return ($change >= 0 ? '+' : '').round($change, 1).'% from last month';
    }

    protected function getPostsTrendIcon(): string
    {
        $lastMonth = Post::whereBetween('created_at', [now()->subMonth(), now()])->count();
        $previousMonth = Post::whereBetween('created_at', [now()->subMonths(2), now()->subMonth()])->count();

        return $lastMonth >= $previousMonth ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
    }

    protected function getPostsTrendColor(): string
    {
        $lastMonth = Post::whereBetween('created_at', [now()->subMonth(), now()])->count();
        $previousMonth = Post::whereBetween('created_at', [now()->subMonths(2), now()->subMonth()])->count();

        return $lastMonth >= $previousMonth ? 'success' : 'danger';
    }

    protected function getUsersTrend(): string
    {
        $lastMonth = User::whereBetween('created_at', [now()->subMonth(), now()])->count();
        $previousMonth = User::whereBetween('created_at', [now()->subMonths(2), now()->subMonth()])->count();

        if ($previousMonth == 0) {
            return $lastMonth > 0 ? '+100%' : 'No change';
        }

        $change = (($lastMonth - $previousMonth) / $previousMonth) * 100;

        return ($change >= 0 ? '+' : '').round($change, 1).'% from last month';
    }

    protected function getUsersTrendIcon(): string
    {
        $lastMonth = User::whereBetween('created_at', [now()->subMonth(), now()])->count();
        $previousMonth = User::whereBetween('created_at', [now()->subMonths(2), now()->subMonth()])->count();

        return $lastMonth >= $previousMonth ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
    }

    protected function getUsersTrendColor(): string
    {
        $lastMonth = User::whereBetween('created_at', [now()->subMonth(), now()])->count();
        $previousMonth = User::whereBetween('created_at', [now()->subMonths(2), now()->subMonth()])->count();

        return $lastMonth >= $previousMonth ? 'success' : 'danger';
    }

    protected function getCommentsTrend(): string
    {
        $lastMonth = Comment::whereBetween('created_at', [now()->subMonth(), now()])->count();
        $previousMonth = Comment::whereBetween('created_at', [now()->subMonths(2), now()->subMonth()])->count();

        if ($previousMonth == 0) {
            return $lastMonth > 0 ? '+100%' : 'No change';
        }

        $change = (($lastMonth - $previousMonth) / $previousMonth) * 100;

        return ($change >= 0 ? '+' : '').round($change, 1).'% from last month';
    }

    protected function getCommentsTrendIcon(): string
    {
        $lastMonth = Comment::whereBetween('created_at', [now()->subMonth(), now()])->count();
        $previousMonth = Comment::whereBetween('created_at', [now()->subMonths(2), now()->subMonth()])->count();

        return $lastMonth >= $previousMonth ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
    }

    protected function getCommentsTrendColor(): string
    {
        $lastMonth = Comment::whereBetween('created_at', [now()->subMonth(), now()])->count();
        $previousMonth = Comment::whereBetween('created_at', [now()->subMonths(2), now()->subMonth()])->count();

        return $lastMonth >= $previousMonth ? 'success' : 'danger';
    }

    protected function getPostsChartData(): array
    {
        return $this->getLastSevenDaysData(Post::class);
    }

    protected function getUsersChartData(): array
    {
        return $this->getLastSevenDaysData(User::class);
    }

    protected function getCommentsChartData(): array
    {
        return $this->getLastSevenDaysData(Comment::class);
    }

    protected function getLastSevenDaysData(string $model): array
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            $count = $model::whereDate('created_at', $date)->count();
            $data[] = $count;
        }

        return $data;
    }

    protected function getActiveUsers(): string
    {
        $activeUsers = User::where(function ($query) {
            $query->whereHas('posts', function ($q) {
                $q->where('created_at', '>=', now()->subDays(30));
            })->orWhereHas('comments', function ($q) {
                $q->where('created_at', '>=', now()->subDays(30));
            });
        })->count();

        return (string) $activeUsers;
    }

    protected function getAvgCommentsPerPost(): string
    {
        $totalPosts = Post::count();
        $totalComments = Comment::count();

        if ($totalPosts == 0) {
            return '0';
        }

        return number_format($totalComments / $totalPosts, 1);
    }

    protected function getThisWeekActivity(): string
    {
        $posts = Post::whereBetween('created_at', [now()->startOfWeek(), now()])->count();
        $comments = Comment::whereBetween('created_at', [now()->startOfWeek(), now()])->count();

        return ($posts + $comments).' items';
    }
}
