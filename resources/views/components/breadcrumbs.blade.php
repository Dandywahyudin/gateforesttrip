@props(['items' => []])

<nav aria-label="breadcrumb" class="flex flex-wrap items-center gap-2 text-[11px] font-black uppercase tracking-[0.25em] text-forest-green/45">
    @foreach($items as $index => $item)
        @php
            $isLast = $index === count($items) - 1;
            $label = $item['label'] ?? '';
            $url = $item['url'] ?? null;
        @endphp

        @if($isLast)
            <span class="text-primary">{{ $label }}</span>
        @else
            @if($url)
                <a href="{{ $url }}" class="transition hover:text-primary">{{ $label }}</a>
            @else
                <span>{{ $label }}</span>
            @endif
            <span class="text-forest-green/20">/</span>
        @endif
    @endforeach
</nav>