<div class="d-flex align-items-center">
    <div>
    <img src="{{ asset('images/icon/'.$user->icon) }}" alt="user_icon" style="width: {{ isset($width) ? $width : '40px' }};">
    </div>
    <div class="ml-3">
        <p class="mb-0"><strong>{{ $user->name }}</strong></p>
        <p class="mb-0"><small>{{ $updated_at }}</small></p>
    </div>
</div>
