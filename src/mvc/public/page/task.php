<?php
/** @var $ctrl \bbn\Mvc\Controller */

if ( !empty($ctrl->arguments[0]) ){
  $ctrl->data['id'] = $ctrl->arguments[0];
  $ctrl->obj->data = $ctrl->getModel();
  $ctrl->obj->url = $ctrl->pluginUrl().'/page/task/'.$ctrl->data['id'];
  echo $ctrl
    ->setTitle($ctrl->obj->data['title'])
    ->addJs()
    ->getView();
}
