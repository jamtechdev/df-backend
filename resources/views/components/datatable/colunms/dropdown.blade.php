@props(['options' => [], 'selected' => false, 'id' => null, 'onchange' => null, 'class' => null])
<div>
    <select class="form-select {{ $class }}" data-id="{{ $id }}" onchange="{{ $onchange }}">
        @foreach ($options as $key => $option)
            <option value="{{ $key }}" {{ $selected == $key ? 'selected' : '' }}>{{ $option }}</option>
        @endforeach
    </select>
</div>
