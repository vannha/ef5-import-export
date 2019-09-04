<?php
/**
 * Created by PhpStorm.
 * User: the EF5 team
 * Date: 01/01/2019
 * Time: 10:29 AM
 */
function ef5_git_shell(){

    if(!ef5_git_exists()) return;

    $log = shell_exec('cd '.get_template_directory().' && git reset --hard origin/master 2>&1; git pull 2>&1; git add --all 2>&1; git commit -m Demo 2>&1; git push 2>&1');
    global $import_result;
    $import_result[] = esc_html__('Git sync successfully!', EF5_IE_TEXT_DOMAIN);

}

function ef5_git_exists(){
    $git = get_template_directory() . '/.git';

    if(!is_dir($git)) return false;

    return true;
}