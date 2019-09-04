<?php
/**
 * @template: import-export-handle.php
 * @since: 1.0.0
 * @author: the EF5 Team
 * @create: 01/01/2019
 */
if (!defined('ABSPATH')) {
    die();
}
if (!class_exists('EF5_Import_Export_handle')) {
    class EF5_Import_Export_handle
    {
        public function __construct()
        {
            add_action('init', array($this, 'ef5_ie_template_redirect'), 30);
            add_action('init', array($this, 'ef5_ie_import_woo_term'), 29);
        }

        public function ef5_ie_template_redirect()
        {

            if (!isset($_REQUEST['page']) || $_REQUEST['page'] !== 'ef5-import') {
                return;
            }
            do_action('ef5-ie-before-handle');
            /**
             * Export handle
             *
             */
            if (!empty($_REQUEST['action']) && $_REQUEST['action'] === 'ef5-export' && !empty($_REQUEST['ef5-ie-id']) && !empty($_REQUEST['ef5-ie-data-type'])) {

                $folder_name = sanitize_title($_REQUEST['ef5-ie-id']);
                $folder_dir = ef5_ie_process_demo_folder($folder_name);
                $this->ef5_ie_get_screen_shot($folder_name);
                do_action('ef5-ie-export-start', $folder_dir);
                $this->ef5_ie_export_start($folder_dir);
                /**
                 * Hook ef5-ie-extra-options
                 * Export and import extra options
                 * Return $options ( array( $option_key1 , $option_key1 , $option_key3....) )
                 */
                $options = array();
                $options = apply_filters('ef5_ie_extra_options', $options);
                $demo_info = array(
                    'name' => $_REQUEST['ef5-ie-id'],
                    'link' => !empty($_REQUEST['ef5-ie-link']) ? $_REQUEST['ef5-ie-link'] : '#',
                    'old_domain' => site_url()
                );

                /**
                 * Export demo information
                 */
                ef5_ie_export_demo_info($folder_dir . 'demo-info.json', $demo_info);


                /**
                 * Export woo attributes
                 */
                ef5_woo_attributes_export($folder_dir . 'woo_attributes.json');

                /**
                 * Export extra options
                 */
                ef5_ie_extra_options_export($folder_dir . 'extra-options.json', $options);

                /**
                 * Export main
                 */
                foreach ($_REQUEST['ef5-ie-data-type'] as $type) {
                    switch ($type) {
                        case 'attachment':
                            ef5_ie_media_export($folder_dir);
                            break;
                        case 'widgets':
                            ef5_ie_widgets_save_export_file($folder_dir);
                            break;
                        case 'settings':
                            ef5_ie_setting_export($folder_dir . 'settings.json');
                            break;
                        case 'options':
                            ef5_ie_options_export($folder_dir . 'options.json');
                            break;
                        case 'content':
                            ef5_ie_content_export($folder_dir);
                            break;
                        case 'revslider':
                            ef5_ie_revslider_export($folder_dir);
                            break;
                    }
                }


                ef5_term_meta_export($folder_dir . 'term-meta.json');

                /**
                 * Clear temp
                 */
                ef5_ie_clear_tmp();

                /**
                 * Git sync
                 */
                ef5_git_shell();

                $this->ef5_ie_export_extra_table($folder_dir . 'extra-tables.json');

                do_action('ef5-ie-export-finish', $folder_dir);
            }

            /**
             * Import handle
             *
             */
            if (!empty($_REQUEST['action']) && $_REQUEST['action'] === 'ef5-import' && !empty($_REQUEST['ef5-ie-id'])) {
                $GLOBALS['import_result'] = array();
                set_time_limit(0);
                $folder_name = sanitize_title($_REQUEST['ef5-ie-id']);
                $folder_dir = ef5_ie_process_demo_folder($folder_name);
                $options = array();
                if (file_exists($folder_dir . 'options.json')) {
                    $options = json_decode(file_get_contents($folder_dir . 'options.json'), true);
                }
                $options['folder'] = $folder_dir;
                do_action('ef5-ie-import-start', $folder_dir);
                $this->ef5_ie_import_start($folder_dir);

                //Woocomerce attributes
                ef5_woo_attributes_import($folder_dir . 'woo_attributes.json');

                //attachment
                $manual_import = !empty($_REQUEST['manual_importing']) ? $_REQUEST['manual_importing'] : false;
                ef5_ie_media_import($options, $folder_dir, $manual_import);

                //content
                ef5_ie_content_import($options);

                //settings
                ef5_ie_setting_import($folder_dir . 'settings.json');

                //options
                ef5_ie_options_import($options);

                //widgets
                ef5_ie_widgets_process_import_file($folder_dir);

                //extra options
                ef5_ie_extra_options_import($folder_dir . 'extra-options.json');

                //revslider
                ef5_ie_revslider_import($folder_dir);

                do_action('ef5-ie-import-finish', $folder_dir);

                $this->ef5_ie_crop_images();

                ef5_term_meta_import($folder_dir . 'term-meta.json');
                /**
                 * Save demo id installed
                 */
                ef5_ie_import_finish($_REQUEST['ef5-ie-id']);

                $this->ef5_ie_import_extra_table($folder_dir . 'extra-tables.json', $folder_dir);

                /**
                 * Clear tmp:
                 * $upload_dir['basedir'] . '/cms-attachment-tmp
                 * $upload_dir['basedir'] . '/ef5-ie-demo
                 */
                ef5_ie_clear_tmp();
            }

            do_action('ef5-ie-after-handle');

            /**
             * Download zip file of all demo data
             */
            if (!empty($_REQUEST['ef5-ie-download']) && $_REQUEST['ef5-ie-download'] === 'ef5' && !empty($_REQUEST['action']) && $_REQUEST['action'] === 'ef5-export') {
                $zip_file = ef5_ie_download_demo_zip();
                header("Content-type: application/zip");
                header("Content-Disposition: attachment; filename=demo-data.zip");
                header("Pragma: no-cache");
                header("Expires: 0");
                readfile($zip_file);

                @unlink($zip_file); //delete file after sending it to user

                exit();
            }

            /**
             * Regenerate thumbnails
             */
            if (!empty($_REQUEST['action']) && $_REQUEST['action'] === 'ef5-regenerate-thumbnails') {
                set_time_limit(0);
                $this->ef5_ie_crop_images();
            }

        }

        public function ef5_ie_import_woo_term(){
            $current_id = get_option('ef5_ie_demo_installed',true);
            $term_imported = get_option('ef5_ie_term_imported',"null");
            $folder_name = sanitize_title($current_id);
            $folder_dir = ef5_ie_process_demo_folder($folder_name);
            if($term_imported === "not_imported"){
                ef5_woo_attributes_term_import($folder_dir . 'woo_attributes.json');
            }
        }


        /**
         * Copy screen_shot of demo
         *
         * @param $folder_name
         */
        function ef5_ie_get_screen_shot($folder_name)
        {

            if (is_file(ef5_ie()->theme_dir . $folder_name . '/screenshot.png')) {
                return;
            }

            if (!is_file(get_template_directory() . '/screenshot.png')) {
                return;
            }

            copy(get_template_directory() . '/screenshot.png', ef5_ie()->theme_dir . $folder_name . '/screenshot.png');
        }


        function ef5_ie_export_start($part)
        {
            $css_file = get_template_directory() . '/assets/css/static.css';

            if (file_exists($css_file)) {
                copy($css_file, $part . 'static.css');
            }
        }

        function ef5_ie_import_start($part)
        {
            $css = get_template_directory() . '/assets/css/static.css';

            if (file_exists($part . 'static.css')) {
                copy($part . 'static.css', $css);
            }
        }

        function ef5_ie_crop_images()
        {
            global $import_result;

            /**
             * Crop image
             */
            $query = array(
                'post_type' => 'attachment',
                'posts_per_page' => -1,
                'post_status' => 'inherit',
            );

            $media = new WP_Query($query);
            if ($media->have_posts()) {
                foreach ($media->posts as $image) {
                    if (strpos($image->post_mime_type, 'image/') !== false) {
                        $image_path = get_attached_file($image->ID);
                        $metadata = wp_generate_attachment_metadata($image->ID, $image_path);
                        wp_update_attachment_metadata($image->ID, $metadata);
                    }
                }
                $import_result[] = esc_html__('Crop images successfully!', EF5_TEXT_DOMAIN);
            }
        }

        function ef5_ie_export_extra_table($file)
        {
            global $table_prefix, $wpdb, $wp_filesystem;
            $extra_tables = apply_filters('ef5_ie_extra_tables', array());
            $rs = array();
            if (!empty($extra_tables)) {
                foreach ($extra_tables as $table => $format) {
                    $rs[$table] = $wpdb->get_results('SELECT * FROM `' . $table_prefix . $table . '`', ARRAY_A);
                }
            }

            $file_contents = json_encode($rs);

            $wp_filesystem->put_contents($file, $file_contents, FS_CHMOD_FILE);
        }

        function ef5_ie_import_extra_table($file, $folder_dir)
        {
            global $table_prefix, $wpdb;
            $extra_tables = apply_filters('ef5_ie_extra_tables', array());
            global $import_result;
            if (file_exists($file)) {
                $file_contents = json_decode(ef5_ie_replace_site_url(file_get_contents($file), $folder_dir), true);
                foreach ($file_contents as $table => $datas) {
                    if (!empty($extra_tables[$table])) {
                        $wpdb->query('TRUNCATE TABLE `' . $table_prefix . $table . '`');
                        foreach ($datas as $row) {
                            $wpdb->insert($table_prefix . $table, $row, $extra_tables[$table]
                            );
                        }
                    }
                    $import_result[] = 'Import table "' . $table . '" successfully!';
                }
            }

        }
    }
}