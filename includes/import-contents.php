<?php
/**
 * @Template: import-contents.php
 * @since: 1.0.0
 * @author: the EF5 Team
 * @descriptions:
 * @create: 01/01/2019
 */
if(!defined('ABSPATH')){
    die();
}
function ef5_ie_content_import($options)
{
    $folder = trailingslashit($options['folder'] . 'content');
    global $import_result;
    /* folder not exists. */
    if(!is_dir($folder))
        return false;

    $wp_import = new EF5_Import();

    /* add image placeholder */
    $attachment = empty($options['attachment']) ? ef5_ie_add_placeholder_image() : null;

    /* import files. */

    $wp_import->import($folder . 'content-data.xml', $attachment);

}

function ef5_ie_add_placeholder_image(){

    $attachment_exists = get_page_by_title(esc_html__('Image Placeholder', EF5_TEXT_DOMAIN), OBJECT, 'attachment');

    if($attachment_exists)
        return $attachment_exists->ID ;

    $wp_upload_dir = wp_upload_dir();

    $_default_image = apply_filters('theme_core_ie-placeholder-image', ef5_ie()->plugin_dir . 'assets/theme-core-ie.jpg');

    copy($_default_image, $wp_upload_dir['path'] . '/theme-core-ie.jpg');

    $attachment = array(
        'guid'           => $wp_upload_dir['url'] . '/theme-core-ie.jpg',
        'post_mime_type' => 'image/jpeg',
        'post_title'     => esc_html__('Image Placeholder', EF5_TEXT_DOMAIN),
        'post_status'    => 'inherit'
    );

    $attachment_id = wp_insert_attachment($attachment, $wp_upload_dir['url'] . '/theme-core-ie.jpg');
    wp_update_attachment_metadata( $attachment_id, wp_generate_attachment_metadata( $attachment_id, $wp_upload_dir['path'] . '/theme-core-ie.jpg' ) );

    return $attachment_id;
}

/**
 * replace content.
 *
 * @param $content
 * @param $attachment
 */
function ef5_ie_replace_content($content, $attachment){

    $_replaces = apply_filters('ef5_ie_replace_content', array(), $attachment);

    foreach ($_replaces as $pattern => $_replace){
        $content = preg_replace($pattern, $_replace, $content);
    }

    return $content;
}