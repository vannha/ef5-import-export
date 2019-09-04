<?php
/**
 * @since: 1.0.0
 * @author: the EF5 Team
 * @create: 01/01/2019
 */
?>
<div class="ef5-export-demos">
    <h3><?php echo esc_html__('Export', EF5_TEXT_DOMAIN) ?></h3>
    <form method="post" class="ef5-export-contents">
        <div class="ef5-export-name">
            <input required='' type="text" id="ef5-ie-id" name="ef5-ie-id" placeholder='<?php echo esc_html__('Name', EF5_TEXT_DOMAIN) ?>'>
        </div>
        <div class="ef5-export-link">
            <input required='' type="text" id="ef5-ie-link" name="ef5-ie-link" placeholder='<?php echo esc_html__('Demo Link', EF5_TEXT_DOMAIN) ?>'>
        </div>
        <div class="ef5-export-options">
            <h4><?php echo esc_html__('Select data:', EF5_TEXT_DOMAIN) ?></h4>
            <div class="ef5-export-list-opt">
                <div class="ef5-checkbox-wrap">
                    <div class="ef5-checkbox">
                        <input id="ef5-ie-data-media" name="ef5-ie-data-type[]" type="checkbox" value="attachment" checked="checked">
                        <span></span>
                        <label for="ef5-ie-data-media"><?php esc_html_e('Media', EF5_TEXT_DOMAIN); ?></label>
                    </div>
                </div>
                <div class="ef5-checkbox-wrap">
                    <div class="ef5-checkbox">
                        <input id="ef5-ie-data-widget" name="ef5-ie-data-type[]" type="checkbox" value="widgets"
                               checked="checked">
                        <span></span>
                        <label for="ef5-ie-data-widget"><?php esc_html_e('Widgets', EF5_TEXT_DOMAIN); ?></label>
                    </div>
                </div>
                <div class="ef5-checkbox-wrap">
                    <div class="ef5-checkbox">
                        <input id="ef5-ie-data-setting" name="ef5-ie-data-type[]" type="checkbox" value="options"
                               checked="checked">
                        <span></span>
                        <label for="ef5-ie-data-setting"><?php esc_html_e('WP Settings', EF5_TEXT_DOMAIN); ?></label>
                    </div>
                </div>
                <?php if (class_exists('ReduxFramework')): ?>
                    <div class="ef5-checkbox-wrap">
                        <div class="ef5-checkbox">
                            <input id="ef5-ie-data-option" name="ef5-ie-data-type[]" type="checkbox" value="settings"
                                   checked="checked">
                            <span></span>
                            <label for="ef5-ie-data-option"><?php esc_html_e('Theme Options', EF5_TEXT_DOMAIN); ?></label>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (function_exists('cptui_get_post_type_data')): ?>
                    <div class="ef5-checkbox-wrap">
                        <div class="ef5-checkbox">
                            <input id="ef5-ie-data-posttype" name="ef5-ie-data-type[]" type="checkbox" value="ctp_ui"
                                   checked="checked">
                            <span></span>
                            <label for="ef5-ie-data-posttype"><?php esc_html_e('Post Type', EF5_TEXT_DOMAIN); ?></label>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="ef5-checkbox-wrap">
                    <div class="ef5-checkbox">
                        <input id="ef5-ie-data-content" name="ef5-ie-data-type[]" type="checkbox" value="content"
                               checked="checked">
                        <span></span>
                        <label for="ef5-ie-data-content"><?php esc_html_e('Content', EF5_TEXT_DOMAIN); ?></label>
                    </div>
                </div>
                <?php if (class_exists('RevSlider')): ?>
                    <div class="ef5-checkbox-wrap">
                        <div class="ef5-checkbox">
                            <input id="ef5-ie-data-rev" name="ef5-ie-data-type[]" type="checkbox" value="revslider"
                                   checked="checked">
                            <span></span>
                            <label for="ef5-ie-data-rev"><?php esc_html_e('Slider Revolution', EF5_TEXT_DOMAIN); ?></label>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="ef5-export-btn">
            <input type="hidden" name="action" value="ef5-export">
            <button type="submit"
                    class="button button-primary create-demo"><?php esc_html_e('Create Demo', EF5_TEXT_DOMAIN); ?></button>
            <button type="submit" class="button button-primary download-demo" name="ef5-ie-download"
                    value="ef5"><?php esc_html_e('Download All Demos', EF5_TEXT_DOMAIN); ?></button>
        </div>
    </form>
</div>
