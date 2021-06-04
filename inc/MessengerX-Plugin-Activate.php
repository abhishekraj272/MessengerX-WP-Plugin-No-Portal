<?php

/**
 * @package MessengerX.io WordPress ChatBot Plugin
 */

class MessengerXPluginActivate
{
    public static function activate()
    {
        add_option( 'machaao_wp_token', uniqid(rand(), true) );
        flush_rewrite_rules();
    }
}
