<?php
/*
 * Describe what it does to show you're not that dumb!
 *
 **/

/** @var $ctrl \bbn\mvc\controller */
if ( !isset($ctrl->post['search']) ){
  $ctrl->combo('', [
    'root' => APPUI_TASKS_ROOT.'panel/',
    'lng' => $ctrl->get_model('../lng/search')
  ]);
}
else{
  $ctrl->obj->data = $ctrl->get_model($ctrl->post);
}