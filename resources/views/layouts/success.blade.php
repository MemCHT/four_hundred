@if (session('success'))
<script src="{{ asset('js/success.js') }}"></script>
<script>
    window.addEventListener('DOMContentLoaded', function(){
        success('{{ session("success") }}');
    });
</script>
@endif