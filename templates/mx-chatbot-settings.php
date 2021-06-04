<div class="mx__settings__main mr-3 mt-5">
    <div class="mx__settings__top">
        <h6 style="font-weight: normal; font-size: 18px;">SETTINGS</h6>
        <div>
            <button id="mx-settings-hide-btn" type="button" class="btn btn-outline-dark btn-sm">Hide</button>
            <a href="https://messengerx.readthedocs.io/en/latest/" target="_blank"><button id="mx-settings-hide-more" type="button" class="btn btn-outline-dark btn-sm">Docs</button></a>
        </div>
    </div>
    <hr>

    <div class="mx__settings__form">
        <form>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="inputChatbotStatus">Chatbot Status</label>
                    <select id="inputChatbotStatus" name="mx_bot_status" class="form-control">
                        <option selected>Enable</option>
                        <option>Disable</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="inputComposerStatus">Composer</label>
                    <select id="inputComposerStatus" name="composer_disabled" class="form-control">
                        <option selected>Enable</option>
                        <option>Disable</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="inputPosition">Position</label>
                    <select id="inputPosition" name="mx_bot_position" class="form-control">
                        <option value="right" selected>Right</option>
                        <option value="left">Left</option>
                    </select>
                </div>
            </div>

            <hr>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="inputChatbotName">Chatbot Name</label>
                    <input required readonly type="text" name="displayName" class="form-control" id="inputChatbotName" placeholder="Chatbot Name">
                </div>
                <div class="form-group col-md-7">
                    <label for="inputChatbotDesc">Chatbot Description</label>
                    <input required type="text" name="description" class="form-control" id="inputChatbotDesc" placeholder="Chatbot Description">
                </div>
                <div class="form-group col-md-1">
                    <label for="inputColor">Theme</label>
                    <input required type="color" name="theme_color" class="form-control" id="inputColor">
                </div>
            </div>

            <hr>

            <div class="form-group">
                <div class="">
                    <label for="inputAvtarUrl">Avatar URL</label>
                    <input required type="url" class="form-control" name="image_url" id="inputAvtarUrl" placeholder="https://www.messengerx.io/img/favicon-32x32.png">
                </div>

                <!-- <div class="col-md-6">
                    <label for="inputWebsiteUrl">Website URL</label>
                    <input required type="url" name="domain" class="form-control" id="inputWebsiteUrl" placeholder="">
                </div> -->
            </div>

            <hr>

            <button type="submit" class="btn btn-outline-dark">Save & Update</button>

        </form>
    </div>

</div>

<script>
    $("#mx-settings-hide-btn").click(function() {
        $(".mx__settings__main").toggleClass("display-block");
    });

    $("#inputColor").val(localStorage['mx_chatbot_theme']);
    $("#inputAvtarUrl").val(localStorage['mx_chatbot_imageUrl']);
    $("#inputChatbotName").val(localStorage['mx_chatbot_name']);
    $("#inputChatbotDesc").val(localStorage['mx_chatbot_description']);
    $("#inputWebsiteUrl").val(localStorage['wp_website_url']);

    if (!localStorage.getItem('mx_chatbot_enable') || localStorage.getItem('mx_chatbot_enable') !== 'Enable') {
        $("#inputChatbotStatus").val('Disable');
    }

    if (!localStorage.getItem('mx_chatbot_position') || localStorage.getItem('mx_chatbot_position') === 'left') {
        $("#inputPosition").val('left');
    }

    if (!localStorage.getItem('mx_chatbot_composerDisabled') || localStorage.getItem('mx_chatbot_composerDisabled') !== 'Enable') {
        $("#inputComposerStatus").val('Disable');
    }

    function handleSubmit(event) {
        event.preventDefault();

        const data = new FormData(event.target);

        let chatbot_update_data = Object.fromEntries(data.entries());
        chatbot_update_data['machaao_wp_token'] = localStorage.getItem('machaao_wp_token');
        localStorage.removeItem('machaao_wp_token');

        $.ajax({
            type: "POST",
            url: localStorage['wp_rest_url'] + 'messengerx-chatbot/v1/update',
            contentType: "application/json",
            data: JSON.stringify(chatbot_update_data),
            success: function(res) {
                console.log("🚀 ~ file: mx-chatbot-settings.php ~ line 100 ~ handleSubmit ~ res", res)
                window.location.reload();
            },
            error: function(err) {
                console.log("🚀 ~ file: mx-chatbot-settings.php ~ line 104 ~ handleSubmit ~ err", err)
                window.location.reload();
            }
        });
    }
    const mx__settings__form = document.querySelector('.mx__settings__form');
    mx__settings__form.addEventListener('submit', handleSubmit);
</script>

<style>
    .mx__settings__main {
        display: none;
        background-color: #F1F1F1;
        border-radius: 5px;
        padding: 10px;
        padding-right: 20px;
        padding-left: 20px;
        border: 1px solid;
        font-family: 'Montserrat', sans-serif;
    }

    .mx__settings__top {
        display: flex;
        justify-content: space-between;
    }
</style>