@if ($errors->has($field))
    <span class="invalid-feedback d-block" role="alert">{{ $errors->first($field) }}</span>
@endif
