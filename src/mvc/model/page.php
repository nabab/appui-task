<?php
/*
 * Describe what it does or you're a pussy
 *
 **/

/** @var $model \bbn\mvc\model*/
$s =& $model->inc->session;
$pm = new \bbn\appui\task($model->db);
$mgr = $model->inc->user->get_manager();
$arch = $model->inc->user->get_class_cfg()['arch']['groups'];
$groups = $mgr->groups();
\bbn\x::sort_by($groups, $arch['group'], 'ASC');
return [
  'root' => APPUI_TASKS_ROOT,
  'root_notes' => $model->plugin_url('appui-note').'/',
  'roles' => \bbn\appui\task::get_appui_options_ids('roles'),
  'states' => \bbn\appui\task::get_appui_options_ids('states'),
  'options' => [
    'states' => \bbn\appui\task::get_appui_options_text_value('states'),
    'roles' => \bbn\appui\task::get_appui_options_text_value('roles'),
    'cats' => \bbn\appui\task::cat_correspondances()
  ],
  'categories' => \bbn\appui\task::get_options_tree('cats'),
  'usergroup' => $model->inc->user->get_group(),
  'groups' => $groups,
  'categories' => $model->inc->options->map(function($a){
    $a['is_parent'] = !empty($a['items']);
    if ( $a['is_parent'] ){
      $a['expanded'] = true;
    }
    return $a;
  }, $pm->categories(), 1),
  'media_types' => $model->inc->options->code_options(\bbn\appui\note::get_appui_option_id('media'))
];
