<div class="input-group">
    <input type="text" class="form-control"
        name="{{ $name }}"
        placeholder="{{ $placeholder ?? $name }}"
        value="{{ $value ?? '' }}"
        >
    <div class="input-group-append">
        <button type="submit" class="input-group-text">
            <i class="fa fa-search" aria-hidden="true"></i>
        </button>
    </div>
</div>
