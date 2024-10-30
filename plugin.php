<?php
/*
 * Plugin Name: Mantiq Integration for Slack
 * Plugin URI: https://wpmantiq.com/
 * Description: An integration between Slack and Mantiq that allows you to send messages via Mantiq workflows.
 * Version: 1.0.0
 * Author: Mantiq
 * Text Domain: mantiq
 * Domain Path: languages
 * Requires at least: 5.0
 * Requires PHP: 7.0
 * Tested up to: 5.8.2
 *
 * @package Mantiq
 * @category Core
 * @author Mantiq
 * @version 1.0.0
 */


use Mantiq\Actions\Slack\Messages\SendMessageToSlackChannel;

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('SlackIntegrationWithMantiq')) {
    class SlackIntegrationWithMantiq
    {
        public static function bootstrap(\Mantiq\Plugin $plugin)
        {
            $plugin->loader->addPsr4('Mantiq\\', [__DIR__.'/src']);

            SendMessageToSlackChannel::register();
        }

        /**
         * @param  string  $path
         *
         * @return string
         */
        public static function getPath(string $path = ''): string
        {
            return wp_normalize_path(__DIR__.DIRECTORY_SEPARATOR.$path);
        }

    }

    add_action('mantiq/init', ['SlackIntegrationWithMantiq', 'bootstrap']);
}
