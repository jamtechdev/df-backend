@props(['options' => []])

<div class="d-flex justify-content-center gap-1">
    @foreach ($options as $option)
        @php
            // Extract data-id or fallback to null
            $dataId = $option['data-id'] ?? null;
        @endphp

        @if (isset($option['method']) && $option['method'] === 'DELETE')
            <form action="{{ $option['href'] }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="btn {{ $option['class'] }} btn-sm"
                        @if($dataId) data-id="{{ $dataId }}" @endif
                        @if (!empty($option['confirm_message'])) onclick="return confirm('{{ $option['confirm_message'] }}')" @endif>
                    <i class="{{ $option['icon'] }}"></i> {{ $option['text'] }}
                </button>
            </form>
        @else
            <a href="{{ $option['href'] }}"
               class="btn {{ $option['class'] }} btn-sm"
               @if($dataId) data-id="{{ $dataId }}" @endif
               @if (!empty($option['confirm_message'])) onclick="return confirm('{{ $option['confirm_message'] }}')" @endif>
                <i class="{{ $option['icon'] }}"></i> {{ $option['text'] }}
            </a>
        @endif
    @endforeach
</div>
