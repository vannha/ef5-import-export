<?php
/**
 * @Template: zip-file-and-download.php
 * @since: 1.0.0
 * @author: the EF5 Team
 * @descriptions:
 * @create: 01/01/2019
 */
if(!defined('ABSPATH')){
    die();
}

function ef5_ie_download_demo_zip(){

    $_cache = trailingslashit(ABSPATH . 'wp-content/uploads/ef5-ie-demo');

    if(!is_dir($_cache))
        wp_mkdir_p($_cache);

    if(!class_exists('ZipArchive'))
        exit();

    $zip = new ZipArchive;
    $zip->open($_cache . 'cms-demo-data.zip', ZIPARCHIVE::CREATE | ZipArchive::OVERWRITE);

    ef5_ie_zip_folder(ef5_ie()->theme_dir, $zip);

    $zip->close();

    return $_cache . 'cms-demo-data.zip';
}

function ef5_ie_zip_folder($folder, $zipFile, $sub = '', $remove = array())
{

    if ($zipFile == null) {
        // no resource given, exit
        return false;
    }
    // we start by going through all files in $folder
    $f = scandir($folder);

    $f = array_diff($f, array('..', '.'));

    $sub = !empty($sub) ? $sub . '/' : '';

    foreach ($f as $_f) {

        if (in_array($_f, $remove)) continue;

        if (is_dir($folder . $_f)) {

            $__f = trailingslashit($folder . $_f);

            $zipFile->addEmptyDir($sub . $_f);

            ef5_ie_zip_folder($__f, $zipFile, $sub . $_f);

        } elseif (is_file($folder . $_f)) {
            $zipFile->addFile($folder . $_f, $sub . $_f);
        }
    }
}