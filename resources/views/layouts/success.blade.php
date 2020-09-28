@if (session('success'))
<script src="/js/success.js"></script>
<script>
    window.addEventListener('DOMContentLoaded', function(){
        success('{{ session("success") }}');
    });
</script>
@endif