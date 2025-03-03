<div>
    <label for="{{ $id }}">{{ $name }}</label>
    <select id="{{ $id }}" name="{{ $id }}" required>
        <option value="">اختر</option>
        @foreach ($options as $key => $value)
            <option value="{{ $key }}" @if ($selected == $key) selected @endif>
                {{ $value }}
            </option>
        @endforeach
    </select>
</div>
