<?php

/**
 * @package MessengerX.io WordPress ChatBot Plugin
 * Plugin Name: MessengerX.io WordPress ChatBot Plugin
 * Plugin URI: https://github.com/machaao/MessengerX.io-Wordpress-Chatbot-Plugin
 * Description: A plugin to create Conversational AI Bot for your wordpress website, to engage your bot on your website.
 * Version: 0.1
 * Author: MessengerX.io (Abhishek Raj)
 * Author URI: http://messengerx.io
 **/

if (!defined('ABSPATH')) {
    die;
}

function write_log($log)
{
    if (true === WP_DEBUG) {
        if (is_array($log) || is_object($log)) {
            error_log(print_r($log, true));
        } else {
            error_log($log);
        }
    }
}

if (!class_exists('MessengerX_Chatbot_Plugin')) {

    class MessengerX_Chatbot_Plugin
    {

        public $plugin;

        public function __construct()
        {
            // Hook into the admin menu
            add_action('admin_menu', array($this, 'add_admin_pages'));

            // Hooks onto every page
            // add_action('init', array($this, 'get_rest_base_url'));

            add_action('rest_api_init', function () {
                register_rest_route('messengerx-chatbot/v1', '/createBot', array(
                    'methods' => array('POST'),
                    'callback' => array($this, 'mx_create_bot'),
                ));
            });

            add_action('rest_api_init', function () {
                register_rest_route('messengerx-chatbot/v1', '/logout', array(
                    'methods' => 'POST',
                    'callback' => array($this, 'mx_plugin_logout'),
                ));
            });

            add_action('rest_api_init', function () {
                register_rest_route('messengerx-chatbot/v1', '/data', array(
                    'methods' => 'GET',
                    'callback' => array($this, 'get_mx_chatbot_data'),
                ));
            });

            add_action('rest_api_init', function () {
                register_rest_route('messengerx-chatbot/v1', '/update', array(
                    'methods' => 'POST',
                    'callback' => array($this, 'update_mx_chatbot_data'),
                ));
            });

            add_action('wp_enqueue_scripts', array($this, 'mx_inject_chatbot'));

            $this->plugin = plugin_basename(__FILE__);

            add_filter("plugin_action_links_$this->plugin", array($this, 'settings_link'));

            register_activation_hook(__FILE__, array($this, 'activate'));
            register_deactivation_hook(__FILE__, array($this, 'deactivate'));
        }

        function activate()
        {
            require_once plugin_dir_path(__FILE__) . 'inc/MessengerX-Plugin-Activate.php';
            MessengerXPluginActivate::activate();
        }

        function deactivate()
        {
            require_once plugin_dir_path(__FILE__) . 'inc/MessengerX-Plugin-Deactivate.php';
            MessengerXPluginDeactivate::deactivate();
        }

        public function get_rest_base_url()
        {
            echo '<script>';
            echo 'localStorage["wp_rest_url"] = ' . "'" . get_rest_url(null, "") . "';";
            echo '</script>';
        }

        public function mx_create_bot($data)
        {
            $body = file_get_contents('php://input');

            $headers = array(
                'Content-Type' => 'application/json'
            );

            $args = array(
                'body' => $body,
                'headers' => $headers,
                'timeout' => 45
            );

            $response = wp_remote_post('https://portal-stage.messengerx.io/wp/api/bot/create', $args);

            $botData = json_decode($response['body'], true);

            if ($response['response']['code'] == 200) {
                add_option('mx_chatbot_name', $botData['name']);
                add_option('mx_chatbot_description', $botData['description']);
                add_option('mx_chatbot_displayName', $botData['displayName']);
                add_option('mx_chatbot_theme', $botData['theme_color']);
                add_option('mx_chatbot_imageUrl', $botData['image_url']);
                add_option('mx_chatbot_composerDisabled', $botData['composer_disabled']);
                add_option('mx_chatbot_enable', 'Disable');
                add_option('mx_chatbot_position', 'left');
                add_option('mx_api_key', $botData['token']);
            }
            return $response['response'];
        }

        public function get_mx_chatbot_data()
        {
            $data = [
                'name' => get_option('mx_chatbot_name', null),
                'theme' => get_option('mx_chatbot_theme', '#2196f3'),
                'avatar' => get_option('mx_chatbot_imageUrl', 'https://www.messengerx.io/img/favicon-32x32.png'),
                // 'machaaoKey' => get_option('mx_api_key', null),
                'botEnabled' => get_option('mx_chatbot_enable', 'Disable'),
                'pos' => get_option('mx_chatbot_position', 'left'),
            ];

            return $data;
        }

        public function update_mx_chatbot_data()
        {
            $data = file_get_contents('php://input');

            $data = json_decode($data, true);

            if ($data["machaao_wp_token"] == get_option('machaao_wp_token')) {
                update_option('mx_chatbot_displayName', $data['displayName']);
                update_option('mx_chatbot_description', $data['description']);
                update_option('mx_chatbot_theme', $data['theme_color']);
                update_option('mx_chatbot_imageUrl', $data['image_url']);
                update_option('mx_chatbot_composerDisabled', $data['composer_disabled']);
                update_option('mx_chatbot_enable', $data['mx_bot_status']);
                update_option('mx_chatbot_position', strtolower($data['mx_bot_position']));

                $data["token"] = get_option('mx_api_key');
                $data["type"] = 'wordpress';
                $data["name"] = get_option('mx_chatbot_name');

                if ($data['mx_bot_status'] == 'Disable') {
                    $data["url"] = 'https://wp-dev.machaao.com/machaao/disabled';
                    $data['status'] = 0;
                }

                if ($data['mx_bot_status'] == 'Enable') {
                    $data["url"] = 'https://wp-dev.machaao.com/machaao/incoming';
                    $data['status'] = 1;
                }

                if ($data['composer_disabled'] == 'Enable') {
                    $data['composer_disabled'] = false;
                }

                if ($data['composer_disabled'] == 'Disable') {
                    $data['composer_disabled'] = true;
                }

                $data['domain'] = 'https://zamaswat.com/';

                $headers = array(
                    'Content-Type' => 'application/json'
                );

                write_log($data);

                $data_to_sent = json_encode($data);

                $args = array(
                    'body' => $data_to_sent,
                    'headers' => $headers
                );

                $response = wp_remote_post('https://portal-stage.messengerx.io/wp/api/bot/update', $args);

                // $response = json_decode($response, true);
                write_log($response);
                if ($response['response']['code'] == 200) {
                    return array('message' => 'success', 'code' => 200);
                } else {
                    return array('message' => 'Something went wrong', 'code' => 500);
                }
            }

            return array('message' => 'request not valid', 'code' => 401);;
        }

        public function mx_inject_chatbot()
        {
            wp_register_script('mx-chatbot-injection', plugins_url('assets/js/widget.js', __FILE__));
            wp_enqueue_script('mx-chatbot-injection');
        }

        public function mx_plugin_logout($data)
        {

            $data = file_get_contents('php://input');
            $data = json_decode($data);

            $saved_mx_access_token = get_option('mx_access_token');

            if ($saved_mx_access_token == $data->mx_access_token) {
                return "success";
            } else {
                return "unauthorised";
            }
        }

        public function add_admin_pages()
        {
            // Add the menu item and page
            $page_title = 'MessengerX Chatbot Settings';
            $menu_title = 'MessengerX Chatbot';
            $capability = 'manage_options';
            $slug = 'messengerx_chatbot_settings';
            $callback = array($this, 'admin_index');
            $icon = 'dashicons-admin-plugins';
            $position = 100;

            add_menu_page($page_title, $menu_title, $capability, $slug, $callback, $icon, $position);
        }

        public function admin_index()
        {
            require_once plugin_dir_path(__FILE__) . 'templates/index.php';
        }

        public function settings_link($links)
        {
            $setting_link = '<a href="admin.php?page=messengerx_chatbot_settings">Settings </a>';
            array_push($links, $setting_link);

            return $links;
        }
    }
}


$api_token = get_option('mx_machaaokey', NULL);

if ($api_token != NULL) {
    $curl_h = curl_init('https://ganglia-dev.machaao.com/v1/bots/details/' . $api_token);



    $api_token = 'api_token: ' . $api_token;



    curl_setopt(
        $curl_h,
        CURLOPT_HTTPHEADER,
        array(
            $api_token,
            'Content-Type: application/json'
        )
    );

    curl_setopt($curl_h, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($curl_h);
    curl_close($curl_h);

    $bot_details = json_decode($response);
}

new MessengerX_Chatbot_Plugin();
