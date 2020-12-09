<style>
    #submitPopup{
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: 1001;
        background-color: rgba(0,0,0,0.3);
    }
    #submitPopup > div{
        width: 100%;
        height: 100%;
    }

    #submitPopup .card .card-body{
        padding: 4em 2em;
        text-align: center;
    }

    #submitPopup .card .card-body h5{
        font-weight: bold;
    }
    #submitPopup .card .card-body .d-flex{
        width: 20em;
    }
    #submitPopup .card .card-body .d-flex button{
        flex: 1;
        margin: 0 0.25em;
        padding-top: 0.125em;
        padding-bottom: 0.125em;
    }
</style>

<div id="submitPopup" class="cover-background">
    <div class="d-flex justify-content-center align-items-center">
        <div class="card">
            <div class="card-body">
                <h5 class="mb-4">{{ $message }}</h5>
                <p>{{ $sub_message ?? '' }}</p>
                <div class="d-flex">
                    <button id="submitReject" class="btn btn-outline-primary">
                        {{ $reject ?? 'いいえ' }}
                    </button>
                    <button id="submitAccept" class="btn btn-outline-danger">
                        {{ $accept ?? 'はい' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('DOMContentLoaded', () => {
        const rejectBtn = document.getElementById('submitReject');

        rejectBtn.addEventListener('click', submitReject);
    });

    const submitReject = (event) => {
        const popup = document.getElementById('submitPopup');
        popup.style.display = 'none';
    };
</script>
