@props(['data' => [], 'width' => 100])

<div style="min-width: {{ $width }}px !important;">
    <div>
        @foreach ($data as $item)
            @php
                $dataAttributes = '';
                if (!empty($item['data'])) {
                    foreach ($item['data'] as $attrKey => $attrValue) {
                        $dataAttributes .= ' data-' . $attrKey . '="' . e($attrValue) . '"';
                    }
                }
            @endphp
            <a href="{{ $item['href'] }}" 
               class="btn text-white btn-sm {{ $item['class'] }}" 
               {!! $dataAttributes !!}>
                <i class="{{ $item['icon'] }}"></i> {{ $item['text'] }}
            </a>
        @endforeach
    </div>
</div>
