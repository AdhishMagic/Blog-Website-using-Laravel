<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            📊 Recent Activity Timeline
        </x-slot>

        <x-slot name="description">
            Latest actions across the platform
        </x-slot>

        <div class="space-y-4">
            @forelse ($this->getActivities() as $activity)
                <div class="flex items-start gap-4 p-3 rounded-lg bg-gray-50 dark:bg-gray-800/50 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                    <div class="flex-shrink-0">
                        <div @class([
                            'w-10 h-10 rounded-full flex items-center justify-center',
                            'bg-primary-100 dark:bg-primary-900/50 text-primary-600 dark:text-primary-400' => $activity['color'] === 'primary',
                            'bg-success-100 dark:bg-success-900/50 text-success-600 dark:text-success-400' => $activity['color'] === 'success',
                            'bg-warning-100 dark:bg-warning-900/50 text-warning-600 dark:text-warning-400' => $activity['color'] === 'warning',
                        ])>
                            <x-filament::icon
                                :icon="$activity['icon']"
                                class="w-5 h-5"
                            />
                        </div>
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2">
                            <p class="text-sm font-semibold text-gray-900 dark:text-gray-100 truncate">
                                {{ $activity['title'] }}
                            </p>
                            <span class="text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                {{ $activity['timestamp']->diffForHumans() }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                            <span class="font-medium">{{ $activity['user'] }}</span>
                            • {{ $activity['description'] }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <x-filament::icon
                        icon="heroicon-o-inbox"
                        class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-600"
                    />
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        No recent activity
                    </p>
                </div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
