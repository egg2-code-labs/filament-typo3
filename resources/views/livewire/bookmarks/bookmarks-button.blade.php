<div>
    <x-filament::dropdown placement="bottom-end">
        <x-slot name="trigger">
            <button
                type="button"
                class="flex items-center gap-1 rounded-lg p-2 text-gray-400 transition hover:bg-gray-100 dark:hover:bg-white/5 focus:outline-none focus:ring-2 focus:ring-primary-500"
                title="{{ __('filament-typo3::bookmarks.bookmarks') }}"
            >
                <x-heroicon-o-bookmark class="size-5" />
                @if (count($this->bookmarks) > 0)
                    <span class="text-xs font-medium text-gray-700 dark:text-gray-200">
                        {{ count($this->bookmarks) }}
                    </span>
                @endif
            </button>
        </x-slot>

        <x-filament::dropdown.list>
            @if (count($this->bookmarks) > 0)
                @foreach ($this->bookmarks as $url => $label)
                    <div class="flex items-center gap-2 px-3 py-2 hover:bg-gray-50 dark:hover:bg-white/5">
                        <a
                            href="{{ $url }}"
                            class="flex-1 truncate text-sm text-gray-700 dark:text-gray-200 hover:underline"
                            title="{{ $label }}"
                        >
                            {{ $label }}
                        </a>
                        <button
                            type="button"
                            wire:click="removeBookmark('{{ addslashes($url) }}')"
                            class="shrink-0 text-gray-400 transition hover:text-danger-500"
                            title="{{ __('filament-typo3::bookmarks.remove') }}"
                        >
                            <x-heroicon-o-x-mark class="size-4" />
                        </button>
                    </div>
                @endforeach
            @else
                <div class="px-3 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                    {{ __('filament-typo3::bookmarks.no_bookmarks') }}
                </div>
            @endif

            <div class="border-t border-gray-100 p-3 dark:border-white/5">
                <button
                    type="button"
                    wire:click="toggleAddForm"
                    class="flex w-full items-center gap-2 text-sm text-primary-600 transition hover:text-primary-500 dark:text-primary-400"
                >
                    <x-heroicon-o-plus class="size-4" />
                    {{ __('filament-typo3::bookmarks.add_bookmark') }}
                </button>

                @if ($showAddForm)
                    <form wire:submit="addBookmark" class="mt-3 space-y-2">
                        <div>
                            <input
                                type="text"
                                wire:model="newBookmarkLabel"
                                placeholder="{{ __('filament-typo3::bookmarks.label_placeholder') }}"
                                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm text-gray-900 focus:border-primary-500 focus:outline-none focus:ring-1 focus:ring-primary-500 dark:border-white/10 dark:bg-gray-900 dark:text-white"
                                required
                            />
                            @error('newBookmarkLabel')
                                <p class="mt-1 text-xs text-danger-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <input
                                type="url"
                                wire:model="newBookmarkUrl"
                                placeholder="{{ __('filament-typo3::bookmarks.url_placeholder') }}"
                                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm text-gray-900 focus:border-primary-500 focus:outline-none focus:ring-1 focus:ring-primary-500 dark:border-white/10 dark:bg-gray-900 dark:text-white"
                                required
                            />
                            @error('newBookmarkUrl')
                                <p class="mt-1 text-xs text-danger-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <button
                            type="submit"
                            class="w-full rounded-lg bg-primary-600 px-3 py-1.5 text-sm font-medium text-white transition hover:bg-primary-500"
                        >
                            {{ __('filament-typo3::bookmarks.save') }}
                        </button>
                    </form>
                @endif
            </div>
        </x-filament::dropdown.list>
    </x-filament::dropdown>
</div>
