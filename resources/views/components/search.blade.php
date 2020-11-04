<form action="{{ $route }}">
    <div class="input-group">
        <input type="text" name="keyword" class="form-control" placeholder="{{ $placeholder }}">
        <div class="input-group-append">
            <button type="submit" class="input-group-text">
                <i class="fa fa-search" aria-hidden="true"></i>
            </button>
        </div>
    </div>
    {{ $slot }}
</form>