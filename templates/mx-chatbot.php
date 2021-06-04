<div class="mt-5 mr-3 chatbot__main">
    <div class="chatbot__top">
        <h6 style="font-weight: normal; font-size: 18px;">MY CHAT APP</h6>
        <div>
            <button type="button" id="create-bot-btn-main" data-toggle="modal" data-target="#mxPopUpModal" class="btn btn-outline-dark btn-sm">Create Chatbot</button>
            <button type="button" id="refresh-stats-btn" class="btn btn-outline-dark btn-sm">Refresh Stats</button>
        </div>
    </div>
    <hr>
    <div class="chatbot__botdetails">
        <!-- Bot data to be inserted here -->
    </div>
</div>

<div class="modal fade" id="mxPopUpModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Bot Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Website URL</label>
                        <input type="text" class="form-control" id="modalInputWebsiteUrl">
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Chatbot Display Name</label>
                        <input class="form-control" id="modelInputDisplayName"></input>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="modalCreateBot" class="btn btn-outline-dark">Create</button>
            </div>
        </div>
    </div>
</div>

<link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
<script src="https://chancejs.com/chance.min.js"></script>
<script>
    document.getElementById('modalInputWebsiteUrl').value = window.location.origin;
    $("#create-bot-btn").prop('disabled', true);
    $("#create-bot-btn-main").prop('disabled', true);
    $("#refresh-stats-btn").prop('disabled', true);

    $(document).on("click", "button.mx-cb-settings-btn", function() {
        $(".mx__settings__main").toggleClass("display-block");
    });

    if (localStorage.getItem('mx_chatbot_name')) {
        $("#refresh-stats-btn").prop('disabled', false);
        $('.chatbot__botdetails').append(
            `<div style="display: flex;" class="chatbot__bot">
            <img style="border-radius: 50%; height: 50px;" alt="" src="${localStorage.getItem('mx_chatbot_imageUrl')}">
            <div style="overflow: hidden;" class="chatbot__details ml-3">
                <p style="font-weight: bold; font-size:14px;" class="chatbot_name">${localStorage.getItem('mx_chatbot_name')}</p>
                <p style="color: grey;" class="chatbot_description">${localStorage.getItem('mx_chatbot_description')}</p>
            </div>
        </div>
        <div style="text-align: center;" class="chatbot__incoming">
            <h6>Incoming Messages</h6>
            <p>56</p>
        </div>

        <div style="text-align: center;" class="chatbot__outgoing">
            <h6>Outgoing Messages</h6>
            <p>56</p>
        </div>

        <div style="text-align: center;" class="chatbot__announcements">
            <h6>Announcements</h6>
            <p>56</p>
        </div>

        <div style="text-align: center;" class="chatbot__requests">
            <h6>Requests</h6>
            <p>56</p>
        </div>

        <div class="chatbot__btn">
            <button type="button" class="btn btn-outline-dark btn-sm mx-cb-settings-btn">Settings</button>
            <a href="https://dev.messengerx.io/${localStorage.getItem('mx_chatbot_name')}" target="_blank"><button type="button" class="btn btn-outline-dark btn-sm">Web Chat</button></a>
        </div>`
        );
        
    } else {
        $("#create-bot-btn").prop('disabled', false);
        $("#create-bot-btn-main").prop('disabled', false);
    }

    $("#modalCreateBot").on("click", function() {
        $.ajax({
            type: "POST",
            url: localStorage['wp_rest_url'] + 'messengerx-chatbot/v1/createBot',
            contentType: "application/json",
            data: JSON.stringify({
                displayName: document.getElementById('modelInputDisplayName').value,
                image_url: "https://www.messengerx.io/img/favicon-32x32.png",
                description: `Welcome to ${window.location.hostname} Bot.`,
                theme_color: "blue",
                host: document.getElementById('modalInputWebsiteUrl').value
            }),
            success: function(res) {
                try {
                    res = JSON.parse(res);
                    console.log(res);
                } catch {
                    console.log(res);
                }
                window.location.reload();
            },
            error: function(err) {
                console.error(err);
                // alert(err);
            }
        });
    });

    //     $.ajax({
    //         type: "POST",
    //         url: "https://portal-stage.messengerx.io/public/api/bot/create",
    //         headers: {
    //             "api_token": atob(localStorage["mx_access_token"]),
    //         },
    //         contentType: "application/json",
    //         data: JSON.stringify({
    //             url: "https://wp-dev.machaao.com/webhooks/machaao/incoming",
    //             image_url: "https://www.messengerx.io/img/favicon-32x32.png",
    //             displayName: "test 23-11-2020",
    //             description: "This is a prototype",
    //             composer_disabled: false,
    //             theme_color: "#2196f3"
    //         }),
    //         success: function(res) {
    //             console.log(res);
    //         },
    //         error: function(err) {
    //             console.error(err);
    //         }
    //     })
    // });

    // $(document).ready(function() {
    //     $.ajax({
    //         type: "GET",
    //         url: 'https://portal-stage.messengerx.io/portalUser',
    //         headers: {
    //             "api_token": atob(localStorage["mx_access_token"])
    //         },
    //         contentType: "application/json",
    //         success: function(data) {
    //             if (data.bots.length > 0) {
    //                 let n = data.bots.length;

    //                 localStorage["mx_bot_description"] = data.bots[n - 1].description;
    //                 localStorage["mx_bot_composer_disabled"] = data.bots[n - 1].composer_disabled;
    //                 localStorage["mx_bot_image_url"] = data.bots[n - 1].image_url;
    //                 localStorage["mx_bot_status"] = data.bots[n - 1].status;
    //                 localStorage["mx_bot_displayName"] = data.bots[n - 1].displayName;

    //                 $("#inputAvtarUrl").val(localStorage['mx_bot_image_url']);
    //                 $("#inputChatbotName").val(localStorage['mx_bot_displayName']);
    //                 $("#inputChatbotDesc").val(localStorage['mx_bot_description']);

    //                 if (!data.bots[n - 1].composer_disabled) {
    //                     $("#inputComposerStatus").val('Disable');
    //                 }

    //                 if (data.bots[n - 1].status == '0') {
    //                     $("#inputChatbotStatus").val('Disable');
    //                 }

    //                 $('.chatbot__botdetails').append(
    //                     `<div style="display: flex;" class="chatbot__bot">
    //         <img style="border-radius: 50%; height: 50px;" alt="" src="${data.bots[n-1].image_url}">
    //         <div style="overflow: hidden;" class="chatbot__details ml-3">
    //             <p style="font-weight: bold; font-size:14px;" class="chatbot_name">${data.bots[n-1].displayName}</p>
    //             <p style="color: grey;" class="chatbot_description">${data.bots[n-1].description}</p>
    //         </div>
    //     </div>
    //     <div style="text-align: center;" class="chatbot__incoming">
    //         <h6>Incoming Messages</h6>
    //         <p>56</p>
    //     </div>

    //     <div style="text-align: center;" class="chatbot__outgoing">
    //         <h6>Outgoing Messages</h6>
    //         <p>56</p>
    //     </div>

    //     <div style="text-align: center;" class="chatbot__announcements">
    //         <h6>Announcements</h6>
    //         <p>56</p>
    //     </div>

    //     <div style="text-align: center;" class="chatbot__requests">
    //         <h6>Requests</h6>
    //         <p>56</p>
    //     </div>

    //     <div class="chatbot__btn">
    //         <button type="button" class="btn btn-outline-dark btn-sm mx-cb-settings-btn">Settings</button>
    //         <a href="https://dev.messengerx.io/${data.bots[0].name}" target="_blank"><button type="button" class="btn btn-outline-dark btn-sm">Web Chat</button></a>
    //     </div>`
    //                 );
    //             }


    //             if (data.bots.length > 0) {
    //                 $("#refresh-stats-btn").prop('disabled', false);
    //             }
    //             if (data.bots.length < data.botLimit) {
    //                 $("#create-bot-btn").prop('disabled', false);
    //                 $("#create-bot-btn-main").prop('disabled', false);
    //             }

    //         },
    //         error: function(data) {
    //             console.error(data);
    //         }
    //     });
    // });
</script>
<!-- F4EFD9 -->
<style>
    body {
        background-color: #F1F1F1;
    }

    nav {
        font-family: 'Montserrat', sans-serif;
    }

    .chatbot__main {
        background-color: #F1F1F1;
        border-radius: 5px;
        padding: 10px;
        padding-right: 20px;
        padding-left: 20px;
        border: 1px solid;
        font-family: 'Montserrat', sans-serif;
    }

    .display-none {
        display: none !important;
    }

    .display-block {
        display: block !important;
    }

    .chatbot__top {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .chatbot__bot {
        text-overflow: ellipsis;
        overflow: hidden;
        white-space: nowrap;
        width: 200px;
    }

    .chatbot__stats {
        justify-content: space-between;
        display: flex;
    }

    .chatbot__botdetails {
        display: flex;
        justify-content: space-between;
    }
</style>