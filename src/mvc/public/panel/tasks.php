<?php
/** @var $ctrl \bbn\mvc\controller */

if ( !empty($ctrl->arguments[0]) ){
  $ctrl->data['id'] = $ctrl->arguments[0];
  $ctrl->obj->data = $ctrl->get_model();
  $ctrl->obj->data['root'] = APPUI_TASKS_ROOT;
  $ctrl->obj->url = 'tasks/'.$ctrl->data['id'];
  echo $ctrl
    ->set_title($ctrl->obj->data['info']['title'])
    ->add_js()
    ->get_view();
}