@props(['data' => [], 'width' => 100])

<div style="min-width: {{ $width }}px !important;">
    <div>
        @foreach ($data as $item)
            <a href="{{ $item['href'] }}" class="btn text-white btn-sm {{ $item['class'] }}">
                <i class="{{ $item['icon'] }}"></i>
                {{ $item['text'] }}
            </a>
        @endforeach
    </div>
</div>
