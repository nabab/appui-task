<?php
/** @var $ctrl \bbn\mvc\controller */

if ( !empty($ctrl->arguments[0]) ){
  $ctrl->data['id'] = $ctrl->arguments[0];
  $ctrl->obj->data = $ctrl->get_model();
  $ctrl->obj->url = $ctrl->plugin_url().'/page/task/'.$ctrl->data['id'];
  echo $ctrl
    ->set_title($ctrl->obj->data['title'])
    ->add_js()
    ->get_view();
}
