<?php
/*
 * Describe what it does to show you're not that dumb!
 *
 **/

/** @var $ctrl \bbn\Mvc\Controller */
if ( !isset($ctrl->post['search']) ){
  $ctrl->combo('', [
    'root' => APPUI_TASKS_ROOT.'page/',
    'lng' => $ctrl->getModel('../lng/search')
  ]);
}
else{
  $ctrl->obj->data = $ctrl->getModel($ctrl->post);
}