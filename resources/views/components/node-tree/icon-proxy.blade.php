@php
    use App\Models\Enums\PageDoctypesEnum;
@endphp

@props(['doctype', 'isHidden'])

@php
    $styles = [
   // TODO: We need another solution for this, it does the job, but it looks like 💩
//       'stroke: rgb(var(--danger-500))' => $isHidden === true,
//       'stroke: rgb(var(--success-500))' => $isHidden === false
    ];
@endphp

<div class="icon">
    @switch($doctype)
        @case(PageDoctypesEnum::STANDARD)
            <x-heroicon-o-document
                @style($styles) />
            @break
        @case(PageDoctypesEnum::EXTERNAL_LINK)
            <x-heroicon-o-link
                @style($styles) />
            @break
        @case(PageDoctypesEnum::SHORTCUT)
            <x-heroicon-o-arrow-top-right-on-square
                @style($styles) />
            @break
        @case(PageDoctypesEnum::LOGO)
            <x-heroicon-o-photo
                @style($styles) />
            @break
        @case(PageDoctypesEnum::FOLDER)
            <x-heroicon-o-folder
                @style($styles) />
            @break
        @default
            <x-heroicon-o-rectangle-stack
                @style($styles) />
    @endswitch
</div>
