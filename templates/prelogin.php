<nav style="border-radius: 5px;" class="navbar navbar-dark bg-dark mt-3 mr-3 pl-3">
    <a class="navbar-brand ml-5" href="https://www.messengerx.io/">
        <img src="https://www.messengerx.io/img/logo.png" width="100" height="30" alt="">
    </a>
</nav>

<div class="container">

    <center class="mt-5 pb-3 pt-3 mx_upper_center">

        <b>
            <h2>Deploy Chatbot for your Wordpress website</h2>
        </b>

        <b>
            <p class="mt-3">Set up your MessengerX.io account to enable Chatbot on this site.<p><b>
                        <a target="_blank" href="https://portal-stage.messengerx.io/"><button id="setUpBtn" class="btn btn-outline-dark btn-lg mt-3">Set up MessengerX.io account</button></a>
    </center>

    <center class="mt-3 pt-3 pb-3 mx_lower_center">
        <button id="enter_access_token_button" class="btn btn-outline-dark btn-sm access-token-btn">Enter Access Token</button>
        <div class="input-group pt-3 pb-3 pr-5 pl-5 token-submit-main">
            <input id="mx-access-token" type="text" class="form-control" placeholder="Enter your access token" aria-label="Recipient's username" aria-describedby="basic-addon2">
            <div class="input-group-append">
                <button id="mx_token_submit_btn" class="btn btn-outline-dark" type="button">Login</button>
            </div>
        </div>
    </center>


</div>

<link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">

<script>
    toastr.options.closeButton = true;
    toastr.options.preventDuplicates = true;
    toastr.options.positionClass = "toast-bottom-right";
    toastr.options.newestOnTop = false;

    $("#enter_access_token_button").click(function() {
        $("#enter_access_token_button").css("display", "none");
        $(".token-submit-main").css("display", "flex");
    });

    $("#mx_token_submit_btn").click(function() {
        $("#mx_token_submit_btn").prop('disabled', true);
        let mx_access_token = $("#mx-access-token").val();

        if (!mx_access_token || !mx_access_token.trim()) {
            toastr.clear();
            toastr.error('Please enter the access token');
        } else {
            toastr.info('Please Wait....');

            $.ajax({
                type: "GET",
                url: 'https://portal-stage.messengerx.io/portalUser',
                headers: {
                    "api_token": mx_access_token
                },
                contentType: "application/json",
                success: function(data) {
                    if (!data.verified) {
                        toastr.warning('Please verify your email before logging in.');
                    } else {
                        toastr.info("Logging in....");
                        console.log(data);
                        data["mx_access_token"] = mx_access_token;
                        data["mx_user_bot_count"] = data.bots.length;
                        console.log(data.bots.length);

                        let fName = data.firstName;

                        $.ajax({
                            type: "POST",
                            url: `${localStorage["wp_rest_url"]}messengerx-chatbot/v1/token`,
                            data: JSON.stringify(data),
                            dataType: "json",
                            contentType: "application/json",
                            success: function(data) {
                                toastr.clear();
                                toastr.success(`Logged in as ${fName}`);
                                location.reload();
                            },
                            error: function(data) {
                                toastr.error('Something went wrong, Please contact support.');
                                $("#mx_token_submit_btn").prop('disabled', true);
                            }
                        });
                    }

                },
                error: function(data) {
                    $("#mx_token_submit_btn").prop('disabled', false);

                    if (data.status == 404) {
                        toastr.clear();
                        toastr.error("Access Token invalid");
                    }
                }
            })
        }

    });
</script>


<style>
    center {
        font-family: 'Montserrat', sans-serif;
        background-color: #F4EFD9;
        border-radius: 5px;
        border: 1px solid;
    }

    nav {
        font-family: 'Montserrat', sans-serif;
    }

    .token-submit-main {
        display: none;
        transition: all 0.2s ease-in-out;
    }

    .access-token-btn {
        transition: all 0.2s ease-in-out;
    }

    body {
        background-color: #F4EFD9;
    }
</style>