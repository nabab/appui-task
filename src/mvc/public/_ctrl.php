<?php
/*
 * Describe what it does or get another gastro-enterite!
 *
 **/

/** @var $ctrl \bbn\mvc\controller */

// cd ~/_lib/vendor/bbn/bbn-task/src
// find . -iname "*.php" | xargs xgettext
// msgfmt -o appui_task.mo appui_task.po
// Better:
// 1st time
// find ../../../mvc -iname "*.php" | xargs xgettext -d appui_task -p ./
// after with -j to j0oin with existing
// find ../../../mvc -iname "*.php" | xargs xgettext -d appui_task -j
// msgfmt -o appui_task.mo appui_task.po
if ( !defined('APPUI_TASKS_ROOT') ){
  define('APPUI_TASKS_ROOT', $ctrl->plugin_url('appui-task').'/');
}
bindtextdomain('appui_task', BBN_LIB_PATH.'bbn/appui-task/src/locale');
setlocale(LC_ALL, "fr_FR.utf8");
textdomain('appui_task');

return 1;