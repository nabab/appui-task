<?php
/*
 * Describe what it does to show you're not that dumb!
 *
 **/

/** @var $ctrl \bbn\mvc\controller */

$templates = \bbn\file\dir::get_files($ctrl->plugin_path().'mvc/html/templates');
$ctrl->data['templates'] = [];
if ( !empty($templates) ){
  $ctrl->data['templates'] = array_map(function($t)use($ctrl){
    return [
      'id' => basename($t, '.php'),
      'html' =>$ctrl->get_view('./templates/'.basename($t, '.php'))
    ];
  }, $templates);
}

$ctrl->obj->url = APPUI_TASKS_ROOT . 'page';
$ctrl
  ->set_color('#000', '#FFF')
  ->set_icon('fa fa-bug')
  ->combo(_("Projects"), true);