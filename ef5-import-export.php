<?php
/**
 * Plugin Name: EF5 Import Export
 * Plugin URI: http://untheme.net/
 * Description: EF5 Import Export helping to create demo data package and setup demo data for clients site.
 * Version: 1.0.
 * Author: the EF5 Team
 * Author URI: http://untheme.net/
 * License: GPLv2
 * Text Domain: ef5-import-export
 */
if (!defined('ABSPATH')) {
    exit();
}
define('EF5_IE_TEXT_DOMAIN','ef5-import-export');

if (!class_exists('EF5_Import_Export')) {

    /**
     * Main Class EF5_Import_Export
     *
     * @since 1.0.0
     *
     * @description: Public EF5_Import_Export:: or GLOBAL ef5_ie()
     *
     * @author: the EF5 Team
     *
     * @create: 15 November, 2017
     */
    class EF5_Import_Export
    {
        public $file;
        public $basename;
        public $plugin_dir;
        public $plugin_url;
        public $assets_dir;
        public $assets_url;
        public $theme_dir;
        public $theme_url;

        public static $instance;

        /**
         * @return EF5_Import_Export
         */
        public static function instance()
        {
            if (is_null(self::$instance)) {
                self::$instance = new EF5_Import_Export();
                self::$instance->setup_globals();
                self::$instance->includes();
                self::$instance->setup_actions();
            }

            return self::$instance;
        }

        private function setup_globals()
        {
            $this->file = __FILE__;

            /* base name. */
            $this->basename = plugin_basename($this->file);

            /* base plugin. */
            $this->plugin_dir = plugin_dir_path($this->file);
            $this->plugin_url = plugin_dir_url($this->file);

            /* base assets. */
            $this->assets_dir = trailingslashit($this->plugin_dir . 'assets');
            $this->assets_url = trailingslashit($this->plugin_url . 'assets');

            $this->theme_dir = trailingslashit(get_template_directory() . '/inc/demo-data');
            $this->theme_url = trailingslashit(get_template_directory_uri() . '/inc/demo-data');

        }

        function ef5_ie_menu_handle()
        {
            $current_theme = wp_get_theme();
            $this->theme_name = $current_theme->get('Name');
            $this->theme_text_domain = $current_theme->get('TextDomain');
            add_submenu_page('admin.php', esc_html__('Import Demo1', EF5_IE_TEXT_DOMAIN), esc_html__('Import Demo2', EF5_IE_TEXT_DOMAIN), 'manage_options', 'ef5-import', array($this, 'ef5_import_demo_page'));
        }

        public function ef5_import_demo_page()
        {
            $export_mode = $this->ef5_ie_enable_export_mode();
            include_once ef5_ie()->plugin_dir . 'templates/import-page.php';
        }


        function ef5_ie_enable_export_mode()
        {
            return apply_filters('ef5_ie_export_mode', false);
        }

        private function includes()
        {
            global $wp_filesystem;

            add_action('admin_menu', array($this, 'ef5_ie_menu_handle'),100);
            add_action('admin_enqueue_scripts', array($this, 'ef5_ie_enqueue_scripts'));

            /**
             * Add WP_Filesystem Class
             *
             */
            if (!class_exists('WP_Filesystem')) {
                require_once(ABSPATH . 'wp-admin/includes/file.php');
                WP_Filesystem();
            }


            // Load Importer API
            require_once ABSPATH . 'wp-admin/includes/import.php';

            if (!class_exists('WP_Importer'))
                require_once ABSPATH . 'wp-admin/includes/class-wp-importer.php';


            require_once ABSPATH . 'wp-admin/includes/post.php';

            require_once ABSPATH . 'wp-admin/includes/comment.php';

            require_once ABSPATH . 'wp-admin/includes/media.php';

            require_once ABSPATH . 'wp-admin/includes/image.php';

            require_once ABSPATH . 'wp-admin/includes/taxonomy.php';

            // include WXR file parsers
            require ef5_ie()->plugin_dir . 'includes/api/parsers.php';

            /* class WP_Import not exists */
            if (!class_exists('EF5_Import'))
                require_once ef5_ie()->plugin_dir . 'includes/api/wordpress-importer.php';

            /**
             * Require extra functions file
             */
            require_once $this->plugin_dir . 'includes/extra-functions.php';
            /**
             * Require export contents handle
             */
            require_once $this->plugin_dir . 'includes/export.php';

            /**
             * Require import contents handle
             */
            require_once $this->plugin_dir . 'includes/import-contents.php';

            /**
             * Require media handle
             */
            require_once $this->plugin_dir . 'includes/attachments.php';

            /**
             * Require zip file and download handle
             */
            require_once $this->plugin_dir . 'includes/zip-file-and-download.php';

            /**
             * Require widget handle
             */
            require_once $this->plugin_dir . 'includes/widgets.php';

            /**
             * Require theme options handle
             */
            require_once $this->plugin_dir . 'includes/settings.php';


            /**
             * Require wp options handle
             */
            require_once $this->plugin_dir . 'includes/options.php';


            /**
             * Require wp options handle
             */
            require_once $this->plugin_dir . 'includes/revslider.php';


            /**
             * Require clear tmp folder
             */
            require_once $this->plugin_dir . 'includes/clear-folder.php';


            /**
             * Require term handlers
             */
            require_once $this->plugin_dir . 'includes/term-handlers.php';

            /**
             * Require woocommerce attributes handles
             */
            require_once $this->plugin_dir . 'includes/woo_attributes_handles.php';

            /**
             * Require git sync
             */
            require_once $this->plugin_dir . 'includes/git.php';


            /**
             * Require reset demo data
             */
            require_once $this->plugin_dir . 'includes/wp-reset.php';


            /**
             * Add EF5_Import_Export_redirect_handle Class
             *
             */
            if (!class_exists('EF5_Import_Export_handle')) {
                require_once($this->plugin_dir . 'includes/import-export-handle.php');
                new EF5_Import_Export_handle();
            }

        }

        private function setup_actions()
        {
        }

        function pp_load_textdomain()
        {
            $language_folder = basename(dirname(__FILE__)) . '/languages';
            load_plugin_textdomain(EF5_IE_TEXT_DOMAIN, false, $language_folder);
        }


        function get_all_demo_folder()
        {

            if (!is_dir($this->theme_dir))
                return false;

            $files = scandir($this->theme_dir, 1);

            return array_diff($files, array('..', '.', 'attachment'));
        }

        function ef5_ie_enqueue_scripts()
        {
            if (isset($_REQUEST['page']) && $_REQUEST['page'] === 'ef5-import') {
                wp_enqueue_style('ef5-ie.css', $this->plugin_url . 'assets/ef5-ie.css');
                wp_enqueue_script('ef5-ie.js', $this->plugin_url . 'assets/ef5-ie.js', array(), 'all', true);
            }
        }
    }

    function ef5_ie()
    {
        return EF5_Import_Export::instance();
    }

    $GLOBALS['ef5_ie'] = ef5_ie();
}