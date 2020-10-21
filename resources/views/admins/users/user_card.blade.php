<div class="card user-card">

    <div class="card-header">{{ $user->name }}</div>

    <div class="card-body">

        <img src="{{ asset('images/icon/'.$user->icon) }}" alt="icon">
        
        <div class="row">
            <p class="col-md-4">メール</p>
            <p class="col-md-8 text-right">{{ $user->email }}</p>
        </div>

        <div class="row">
            <p class="col-md-4">ステータス</p>
            <div class="col-md-8 text-right">@status(['color' => $user->status->color]) {{ $user->status->name }} @endstatus</div>
        </div>

        <div class="row">
            <div class="offset-md-6"></div>
            <div class="col-md-6 text-right">
                @article{{ $user->articles_count }}@endarticle
                @favorite{{ $user->favorites_count }}@endfavorite
            </div>
        </div>

        <div class="text-center">
            <a href="{{ route('admins.users.show', ['user' => $user->id]) }}" class="btn btn-secondary mx-auto">詳細</a>
        </div>
    </div>
</div>
