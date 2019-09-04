<?php
/**
 * @Template: Import demo page
 * @version: 1.0.0
 * @author: the EF5 Team
 * @descriptions: Display for import demo page in Dashboard framework
 */

$demo_list = ef5_ie()->get_all_demo_folder();
$current_demo_installed = get_option('ef5_ie_demo_installed', '');
$path = ef5_ie()->theme_dir;
$url = ef5_ie()->theme_url;


$_search = array('M', 'G', 'K', 'm', 'g', 'k');

$memory_limit = (int)str_replace($_search, null, ini_get("memory_limit"));
$max_time = (int)ini_get("max_execution_time");
$max_time_text = $max_time === 0 ? 'unlimit' : $max_time;
$post_max_size = (int)str_replace($_search, null, ini_get('post_max_size'));
$php_ver = PHP_VERSION;
$_notice = ($memory_limit < 128 || ($max_time < 60 && $max_time !==0 )|| $post_max_size < 32) ? 'ef5-ie-warning' : 'ef5-ie-good';

?>
<div class="wrap">
    <div class="ef5-ie-dashboard">
        <div class="ef5-field-info <?php echo esc_attr($_notice); ?>">
            <table class="ef5-server-info">
                <tr>
                    <th><?php esc_html_e('PHP Version:', EF5_TEXT_DOMAIN); ?></th>
                    <td><i class="dashicons dashicons-yes" style="color: #31f531"></i></td>
                    <td style="color: #0d880b"><?php echo esc_html($php_ver); ?></td>
                </tr>
                <tr>
                    <th><?php esc_html_e('Memory Limit:', EF5_TEXT_DOMAIN) ?></th>
                    <?php if ($memory_limit >= 128): ?>
                        <td><i class="dashicons dashicons-yes" style="color: #31f531"></i></td>
                        <td style="color: #0d880b"><?php echo sprintf(esc_html__('Currently: %s (Mb)', ''), $memory_limit); ?></td>
                    <?php else: ?>
                        <td><i class="dashicons dashicons-no" style="color: red"></i></td>
                        <td style="color: red"><?php echo sprintf(esc_html__('Currently: %s (the minimum required 128M)', ''), $memory_limit); ?></td>
                    <?php endif; ?>
                </tr>
                <tr>
                    <th><?php esc_html_e('Max. Execution Time:', EF5_TEXT_DOMAIN) ?></th>
                    <?php if ($max_time >= 60 || $max_time === 0): ?>
                        <td><i class="dashicons dashicons-yes" style="color: #31f531"></i></td>
                        <td style="color: #0d880b"><?php echo sprintf(esc_html__('Currently: %s (s)', ''), $max_time_text); ?></td>
                    <?php else: ?>
                        <td><i class="dashicons dashicons-no" style="color: red"></i></td>
                        <td style="color: red"><?php echo sprintf(esc_html__('Currently: %ss (the minimum required 60s)', ''), $max_time_textcd); ?></td>
                    <?php endif; ?>
                </tr>
                <tr>
                    <th><?php esc_html_e('Max. Post Size:', EF5_TEXT_DOMAIN) ?></th>
                    <?php if ($post_max_size >= 32): ?>
                        <td><i class="dashicons dashicons-yes" style="color: #31f531"></i></td>
                        <td style="color: #0d880b"><?php echo sprintf(esc_html__('Currently: %s (Mb)', ''), $post_max_size); ?></td>
                    <?php else: ?>
                        <td><i class="dashicons dashicons-no" style="color: red"></i></td>
                        <td style="color: red"><?php echo sprintf(esc_html__('Currently: %s (the minimum required 32M)', ''), $post_max_size); ?></td>
                    <?php endif; ?>
                </tr>
            </table>
            <div class="ef5-advance-options">
                <ul class="ef5-options">
                    <li class="ef5-show-manual-import"><span
                                class="dashicons dashicons-media-spreadsheet"></span><?php echo esc_html__("Manual Import", EF5_TEXT_DOMAIN) ?>
                    </li>
                    <li class="ef5-show-regenerate-thumbnail"><span
                                class="dashicons dashicons-images-alt"></span><?php echo esc_html__("Regenerate Thumbnails", EF5_TEXT_DOMAIN) ?>
                    </li>
                    <li class="ef5-advance-reset"><span
                                class="dashicons dashicons-update"></span><?php echo esc_html__("Reset Site", EF5_TEXT_DOMAIN) ?>
                    </li>
                </ul>
                <span class="dashicons dashicons-admin-generic"></span>
                <form method="post" class="ef5-reset-form-advance" style="display: none">
                    <?php wp_nonce_field('ef5-reset', '_wp_nonce'); ?>
                    <input type="hidden" name="action" value="ef5-reset">
                </form>
                <form method="post" class="ef5-regenerate-thumbnail-sm" style="display: none">
                    <input type="hidden" name="action" value="ef5-regenerate-thumbnails">
                </form>
            </div>
            <?php
            include_once ef5_ie()->plugin_dir . 'templates/manual-import-template.php';
            ?>
        </div>
        <div class="ef5-import-demos">
            <div class="ef5-import-contains">
                <?php
                if (!empty($demo_list)):
                    foreach ($demo_list as $demo):
                        $file_demo_info = $path . $demo . '/demo-info.json';
                        $demo_installed = $current_demo_installed === $demo ? true : false;
                        if (file_exists($file_demo_info)):
                            $info_demo = json_decode(file_get_contents($file_demo_info), true);
                            ?>
                            <form method="post" class="ef5-ie-demo-item" data-demo="demo-<?php echo $demo ?>"
                                  id="demo-<?php echo $demo ?>">
                                <div class="ef5-ie-item-inner">
                                    <div class="ef5-ie-image">
                                        <img src="<?php echo $url . $demo . '/screenshot.png' ?>" alt="">
                                        <a class="ef5-ie-preview" href="<?php echo esc_attr($info_demo['link']) ?>"
                                           target="_blank">
                                            <span><?php esc_html_e('View Demo', EF5_TEXT_DOMAIN) ?></span>
                                        </a>
                                    </div>
                                    <div class="ef5-ie-meta">
                                        <h4 class="ef5-ie-demo-title"><?php echo esc_attr($info_demo['name']) ?></h4>
                                        <input type="hidden" name="ef5-ie-id" value="<?php echo esc_attr($demo) ?>">
                                        <input type="hidden" name="action" value="ef5-import">
                                        <button class="ef5-import-btn ef5-import-submit button button-primary"
                                                name="ef5-import-submit"
                                                value="<?php echo base64_encode($demo) ?>"><?php echo $demo_installed === true ? esc_html__('Update Demo', EF5_TEXT_DOMAIN) : esc_html__('Import Demo', EF5_TEXT_DOMAIN) ?></button>
                                        <?php
                                        if ($demo_installed === true) {
                                            wp_nonce_field('ef5-reset', '_wp_nonce');
                                            ?>
                                            <button class="ef5-import-btn ef5-delete-demo button button-primary"
                                                    name="ef5-ie-delete-demo"
                                                    value="<?php echo base64_encode($demo) ?>"><?php esc_html_e('Reset Site', EF5_TEXT_DOMAIN) ?></button>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <div class="ef5-loading" style="display: none">
                                        <span class="ef5-pinner"><span class="fa fa-spinner fa-spin"></span></span>
                                    </div>
                                </div>
                            </form>
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
        </div>
        <?php
        if (!empty($export_mode)) {
            include_once ef5_ie()->plugin_dir . 'templates/export-page.php';
        }
        ?>
    </div>
</div>
