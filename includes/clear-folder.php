<?php
/**
 * @Template: clear-folder.php
 * @since: 1.0.0
 * @author: the EF5 Team
 * @descriptions:
 * @create: 01/01/2019
 */
function ef5_ie_clear_tmp(){

    $upload_dir = wp_upload_dir();

    ef5_ie_delete_directory($upload_dir['basedir'] . '/cms-attachment-tmp');
    ef5_ie_delete_directory($upload_dir['basedir'] . '/ef5-ie-demo');
}

function ef5_ie_delete_directory($dir)
{
    if (!file_exists($dir)) {
        return true;
    }
    if (!is_dir($dir) || is_link($dir)) {
        return unlink($dir);
    }
    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }
        if (!ef5_ie_delete_directory($dir . "/" . $item, false)) {
            chmod($dir . "/" . $item, 0777);
            if (!ef5_ie_delete_directory($dir . "/" . $item, false)) return false;
        };
    }
    return rmdir($dir);
}
function ef5_ie_import_finish($demo_id){
    update_option('ef5_ie_demo_installed',$demo_id);
    global $wp_filesystem,$import_result;
    $file = ef5_ie()->plugin_dir.'assets/log.txt';
    $file_contents = implode(PHP_EOL,$import_result);
    $wp_filesystem->put_contents($file, $file_contents, FS_CHMOD_FILE);
}