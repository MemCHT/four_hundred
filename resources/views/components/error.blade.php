@if($errors->has($name))
    <div class="error text-danger">
        <p>{{ $errors->first($name) }}</p>
    </div>
@endif