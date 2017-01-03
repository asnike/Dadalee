@if (session()->has('flash_notification.message'))
    <div class="alert alert-{{ session('flash_notification.level') }} alert-dismissible flash-message" role="alert">
        <button type="button" class="close" data-dismiss="alert">
            <span aria-hidden="true">&times;</span>
            <span class="sr-only">Close</span>
        </button>
        {{ session('flash_notification.message') }}
    </div>
@endif
