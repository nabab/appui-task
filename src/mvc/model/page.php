<?php
/*
 * Describe what it does or you're a pussy
 *
 **/

/** @var $model \bbn\mvc\model*/
$s =& $model->inc->session;
$pm = new \bbn\appui\tasks($model->db);

return [
  'root' => APPUI_TASKS_ROOT,
  'root_notes' => $model->plugin_url('appui-notes').'/',
  'roles' => \bbn\appui\tasks::get_appui_options_ids('roles'),
  'states' => \bbn\appui\tasks::get_appui_options_ids('states'),
  'options' => [
    'states' => \bbn\appui\tasks::get_appui_options_text_value('states'),
    'roles' => \bbn\appui\tasks::get_appui_options_text_value('roles'),
    'cats' => \bbn\appui\tasks::cat_correspondances()
  ],
  'categories' => \bbn\appui\tasks::get_options_tree('cats'),
  'usergroup' => $model->inc->user->get_group(),
  'groups' => $model->get_model('usergroup/picker'),
  'categories' => $model->inc->options->map(function($a){
    $a['is_parent'] = !empty($a['items']);
    if ( $a['is_parent'] ){
      $a['expanded'] = true;
    }
    return $a;
  }, $pm->categories(), 1),
  'media_types' => $model->inc->options->code_options(\bbn\appui\notes::get_appui_option_id('media'))
];
