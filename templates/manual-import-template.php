<?php
/**
 * @Template: manual-import-template.php.
 * @since: 1.0.0
 * author: the EF5 Team
 * @descriptions:
 * @create: 01/01/2019
 */
?>

<div class="ef5-manual-import-layout ef5-m-hidden" style="display: none">
    <div class="ef5-contain">

        <div class="ef5-loading" style="display: none">
            <span class="ef5-pinner"><span class="fa fa-spinner fa-spin"></span></span>
        </div>
        <span class="dashicons dashicons-dismiss"></span>
        <div class="ef5-contain-wrap">
            <div class="ef5-tabs-head">
                <div class="tabs-demos ef5-mi-active" data-id="select-demo">
                    <span><?php esc_html_e('Select Demo', EF5_IE_TEXT_DOMAIN) ?></span>
                </div>
                <div class="tabs-demos"
                     data-id="attachments">
                    <span><?php esc_html_e('Download Attachment', EF5_IE_TEXT_DOMAIN) ?></span>
                </div>
            </div>
            <div class="ef5-tabs-content">
                <div class="tabs-contents ef5-mi-demo-list" id="select-demo">
                    <?php
                    if (!empty($demo_list)):
                        foreach ($demo_list as $demo):
                            $file_demo_info = $path . $demo . '/demo-info.json';
                            $demo_installed = $current_demo_installed === $demo ? true : false;
                            if (file_exists($file_demo_info)):
                                $info_demo = json_decode(file_get_contents($file_demo_info), true);
                                $link = "#";
                                if (file_exists($path . $demo . '/options.json')) {
                                    $options = json_decode(file_get_contents($path . $demo . '/options.json'), true);
                                    $link = $options['attachment'];
                                }
                                ?>
                                <div class="ef5-mi-demo-item">
                                    <div class="ef5-mi-item-inner">
                                        <div class="ef5-mi-image">
                                            <img src="<?php echo $url . $demo . '/screenshot.png' ?>" alt="">
                                            <div class="ef5-mi-preview">
                                                <h4 class="ef5-mi-demo-title"><?php echo esc_attr($info_demo['name']) ?></h4>
                                                <a class="ef5-mi-select" href="#"
                                                   data-attachment="<?php echo esc_url($link) ?>" data-demo="<?php echo esc_attr($demo) ?>">
                                                    <span><?php esc_html_e('Select', EF5_IE_TEXT_DOMAIN) ?></span>
                                                </a>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            <?php
                            endif;
                        endforeach;
                    else:
                        ?>
                        <div class="ef5-ie-demo-empty">
                            <span class="dashicons dashicons-warning"></span>
                            <h4 class="ef5-ie-notice-empty"><?php echo esc_html__('Demos data is empty') ?></h4>
                        </div>
                    <?php
                    endif;
                    ?>
                </div>
                <div class="tabs-contents" id="attachments">
                    <div class="ef5-mi-demo-item-selected">
                        <div class="ef5-mi-item-inner">
                            <div class="ef5-mi-image ef5-mi-image-selected">
                                <img src="<?php echo ef5_ie()->assets_url . '/ef5-ie.jpg' ?>" alt="">
                                <div class="ef5-mi-preview">
                                    <h4 class="ef5-mi-demo-title-selected"></h4>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="ef5-mi-download-att">
                        <div class="ef5-mi-dl-step step-1">
                            <h4><?php echo esc_html__('Step 1:', EF5_IE_TEXT_DOMAIN) ?></h4>
                            <button id="ef5-download-attachment-btn"
                                    data-attachment="#"><?php echo esc_html__('Download Attachments File', EF5_IE_TEXT_DOMAIN) ?></button>
                        </div>
                        <div class="ef5-mi-dl-step step-2">
                            <h4><?php echo esc_html__('Step 2:', EF5_IE_TEXT_DOMAIN) ?></h4>
                            <p>Please <b>"<?php esc_html_e("Upload", EF5_IE_TEXT_DOMAIN) ?>
                                    "</b> <?php esc_html_e("and", EF5_IE_TEXT_DOMAIN) ?>
                                <b>"<?php esc_html_e("Unzip", EF5_IE_TEXT_DOMAIN) ?>"</b> file
                                to <b><?php echo wp_upload_dir()['basedir'] ?>/</b></p>
                        </div>
                        <div class="ef5-mi-dl-step step-3">
                            <input type="checkbox" id="ef5-accept-unzip-done" value="ef5-accept">
                            <label for="ef5-accept-unzip-done"><?php esc_html_e("I uploaded and unzipped file", EF5_IE_TEXT_DOMAIN) ?></label>
                        </div>
                        <div class="ef5-mi-dl-step step-4">
                            <button><?php esc_html_e("Import Demo Data", EF5_IE_TEXT_DOMAIN) ?></button>
                            <form method="post" style="display: none">
                                <input type="hidden" name="ef5-ie-id" value="<?php echo esc_attr($demo) ?>">
                                <input type="hidden" name="action" value="ef5-import">
                                <input type="hidden" name="manual_importing" value="true">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>