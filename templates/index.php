<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<?php
echo '<script>';
echo 'localStorage.clear();';
echo 'localStorage["wp_rest_url"] = '. "'". get_rest_url(null, "") . "';";
echo 'localStorage["machaao_wp_token"] = '. "'". get_option( "machaao_wp_token" ) . "';";
if (get_option('mx_chatbot_name')) {
    echo 'localStorage["mx_chatbot_name"] = ' ."'". get_option( "mx_chatbot_name" ) . "';";
    echo 'localStorage["mx_chatbot_description"] = ' ."'". get_option( "mx_chatbot_description" ) . "';";
    echo 'localStorage["mx_chatbot_theme"] = ' ."'". get_option( "mx_chatbot_theme" ) . "';";
    echo 'localStorage["mx_chatbot_imageUrl"] = ' ."'". get_option( "mx_chatbot_imageUrl" ) . "';";
    echo 'localStorage["mx_chatbot_enable"] = ' ."'". get_option( "mx_chatbot_enable" ) . "';";
    echo 'localStorage["mx_chatbot_composerDisabled"] = ' ."'". get_option( "mx_chatbot_composerDisabled" ) . "';";
    echo 'localStorage["mx_chatbot_position"] = ' ."'". get_option( "mx_chatbot_position" ) . "';";
}
echo '</script>';
?>




<?php
include('mx-navbar.php');
include('mx-chatbot.php');
include('mx-chatbot-settings.php');
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>