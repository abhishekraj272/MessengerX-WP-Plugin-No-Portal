<?php

/**
 * @package MessengerX.io WordPress ChatBot Plugin
 */

class MessengerXPluginDeactivate
{
    public static function deactivate()
    {
        delete_option('mx_chatbot_name');
        delete_option('mx_chatbot_description');
        delete_option('mx_chatbot_displayName');
        delete_option('mx_chatbot_theme');
        delete_option('mx_chatbot_imageUrl');
        delete_option('mx_chatbot_composerDisabled');
        delete_option('mx_chatbot_enable');
        delete_option('mx_api_key');
        delete_option( 'machaao_wp_token' );
        flush_rewrite_rules();
    }
}
