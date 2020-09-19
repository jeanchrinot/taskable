

    <!-- Then put toasts within -->
    <div id="error-toast" class="toast toast--error" role="alert" aria-live="assertive" aria-atomic="true" data-autohide="false" data-animation="true" style="position: fixed;top:50%;left:50%;transform: translate(-50%,-50%);width: 100%;z-index: -1;">
      <div class="toast-header">
        <span><i class="fa fa-exclamation-circle mr-10"></i> </span>
        <strong class="mr-auto">Error:</strong>
        <!-- <small class="text-muted">just now</small> -->
        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="toast-body">
        There is an error.
      </div>
    </div>

    <div id="message-toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true" style="position: fixed;top:50%;left:50%;transform: translate(-50%,-50%);width: 100%;z-index: -1;">
      <div class="toast-header">
        <img src="..." class="rounded mr-2" alt="...">
        <strong class="mr-auto">Bootstrap</strong>
        <small class="text-muted">2 seconds ago</small>
        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="toast-body">
        Heads up, toasts will stack automatically
      </div>
    </div>