<?php

namespace App\Filament\Widgets;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class PostsUsersCommentsChart extends ChartWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected int $months = 6;

    public static function canView(): bool
    {
        return true;
    }

    public function getHeading(): ?string
    {
        return 'Activity Trends';
    }

    public function getDescription(): ?string
    {
        return 'Posts, Users & Comments over time';
    }

    protected function getData(): array
    {
        $start = now()->subMonths($this->months - 1)->startOfMonth();
        $period = $this->buildPeriod($start);

        $labels = $period->map(fn (Carbon $date) => $date->format('M Y'));

        $postCounts = $this->countsForModel(Post::class, $start);
        $userCounts = $this->countsForModel(User::class, $start);
        $commentCounts = $this->countsForModel(Comment::class, $start);

        return [
            'datasets' => [
                [
                    'label' => 'Posts',
                    'data' => $this->mapCountsOntoPeriod($period, $postCounts),
                    'borderColor' => '#f59e0b', // amber
                    'backgroundColor' => 'rgba(245, 158, 11, 0.3)',
                ],
                [
                    'label' => 'Users',
                    'data' => $this->mapCountsOntoPeriod($period, $userCounts),
                    'borderColor' => '#10b981', // emerald
                    'backgroundColor' => 'rgba(16, 185, 129, 0.3)',
                ],
                [
                    'label' => 'Comments',
                    'data' => $this->mapCountsOntoPeriod($period, $commentCounts),
                    'borderColor' => '#3b82f6', // blue
                    'backgroundColor' => 'rgba(59, 130, 246, 0.3)',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
        ];
    }

    /**
     * @return \Illuminate\Support\Collection<int, Carbon>
     */
    protected function buildPeriod(Carbon $start): Collection
    {
        return collect(range(0, $this->months - 1))
            ->map(fn (int $offset) => $start->copy()->addMonths($offset));
    }

    /**
     * @param  class-string<\Illuminate\Database\Eloquent\Model>  $modelClass
     */
    protected function countsForModel(string $modelClass, Carbon $start): Collection
    {
        /** @var \Illuminate\Support\Collection<string, int> $counts */
        $counts = $modelClass::query()
            ->where('created_at', '>=', $start)
            ->get(['created_at'])
            ->groupBy(fn ($model) => $model->created_at->format('Y-m'))
            ->map->count();

        return $counts;
    }

    /**
     * @param  \Illuminate\Support\Collection<int, Carbon>  $period
     * @param  \Illuminate\Support\Collection<string, int>  $dataset
     * @return array<int, int>
     */
    protected function mapCountsOntoPeriod(Collection $period, Collection $dataset): array
    {
        return $period
            ->map(fn (Carbon $date) => $dataset->get($date->format('Y-m'), 0))
            ->all();
    }
}
