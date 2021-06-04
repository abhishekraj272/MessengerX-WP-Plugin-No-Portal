<nav style="border-radius: 5px;" class="navbar navbar-expand-lg nav-mx-wp navbar-dark bg-dark mt-3 mr-3">
    <a class="navbar-brand" target="_blank" href="https://messengerx.io/">
        <img src="https://www.messengerx.io/img/logo.png" class="ml-5" width="100" height="30" alt="">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse ml-auto" id="navbarNavDropdown">
        <ul class="navbar-nav ml-auto">

            <li class="nav-item dropdown">

            </li>
            <li class="nav-item">
                <a class="nav-link" href="mailto:support@messengerx.io">Support</a>
            </li>
        </ul>
    </div>
</nav>

<style>
.nav-mx-wp {
    background-color: #23282d !important;
}
.nav-mx-wp .navbar-text {
    color: #9ea3a8;
}
</style>

<script>
    toastr.options.closeButton = true;
    toastr.options.preventDuplicates = true;
    toastr.options.progressBar = true;
    toastr.options.positionClass = "toast-bottom-right";


    $('#mx-logout-btn').click(function() {
        $.ajax({
            type: "POST",
            url: `${localStorage["wp_rest_url"]}messengerx-chatbot/v1/logout`,
            data: JSON.stringify({
                "mx_access_token": atob(localStorage["mx_access_token"])
            }),
            dataType: "json",
            contentType: "application/json",
            success: function(data) {
                toastr.success('Logged out');
                localStorage.clear();
                location.reload();
            }
        });
    });
</script>