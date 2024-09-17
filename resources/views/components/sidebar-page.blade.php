@php
    //    dd($this::getResource()::getModel());
    //dd($this->getSidebar(), $this->getSidebarWidths());
        $sidebar = $this->getSidebar();
        $sidebarWidths = $this->getSidebarWidths();
@endphp

<div>
    <div class="mt-8">
        <div class="grid grid-cols-12 gap-4 sm:gap-5 lg:gap-6">
            <div class="col-[--col-span-default]
                        sm:col-[--col-span-sm]
                        md:col-[--col-span-md]
                        lg:col-[--col-span-lg]
                        xl:col-[--col-span-xl]
                        2xl:col-[--col-span-2xl]
                        rounded"
                 style="--col-span-default: span 12;
                        --col-span-sm: span {{ $sidebarWidths['sm'] ?? 12 }};
                        --col-span-md: span {{ $sidebarWidths['md'] ?? 3 }};
                        --col-span-lg: span {{ $sidebarWidths['lg'] ?? 3 }};
                        --col-span-xl: span {{ $sidebarWidths['xl'] ?? 3 }};
                        --col-span-2xl: span {{ $sidebarWidths['2xl'] ?? 3 }};">
                <div class="">
                    <div class="flex items-center rtl:space-x-reverse">
                        @if ($sidebar->getTitle() != null || $sidebar->getDescription() != null)
                            <div class="w-full">
                                @if ($sidebar->getTitle() != null)
                                    <h3 class="text-base font-medium text-slate-700 dark:text-white truncate block">
                                        {{ $sidebar->getTitle() }}
                                    </h3>
                                @endif

                                @if ($sidebar->getDescription())
                                    <p class="text-xs text-gray-400 flex items-center gap-x-1">
                                        {{ $sidebar->getDescription() }}
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>
                    <ul class="@if ($sidebar->getTitle() != null || $sidebar->getDescription() != null) mt-4 @endif space-y-2 font-inter font-medium"
                        wire:ignore>
                        @foreach($sidebar->getPages() as $page)
                            <livewire:filament-typo3::page-tree-page :page="$page" :is-open="true" />
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="col-[--col-span-default]
                        sm:col-[--col-span-sm]
                        md:col-[--col-span-md]
                        lg:col-[--col-span-lg]
                        xl:col-[--col-span-xl]
                        2xl:col-[--col-span-2xl]
                        -mt-8"
                 style="--col-span-default: span 12;
                        --col-span-sm: span {{ $sidebarWidths['sm'] == 12 ? 12 : 12 - ($sidebarWidths['sm'] ?? 3) }};
                        --col-span-md: span {{ $sidebarWidths['md'] == 12 ? 12 : 12 - ($sidebarWidths['md'] ?? 3) }};
                        --col-span-lg: span {{ $sidebarWidths['lg'] == 12 ? 12 : 12 - ($sidebarWidths['lg'] ?? 3) }};
                        --col-span-xl: span {{ $sidebarWidths['xl'] == 12 ? 12 : 12 - ($sidebarWidths['xl'] ?? 3) }};
                        --col-span-2xl: span {{ $sidebarWidths['2xl'] == 12 ? 12 : 12 - ($sidebarWidths['2xl'] ?? 3) }}; margin-top: -2em;">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
