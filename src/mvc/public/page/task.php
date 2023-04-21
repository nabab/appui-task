<?php
/** @var $ctrl \bbn\Mvc\Controller */

if ( !empty($ctrl->arguments[0]) ){
  $ctrl->data['id'] = $ctrl->arguments[0];
  $model = $ctrl->getModel();
  if (empty($model['error'])) {
    $ctrl->obj->data = $model;
    $ctrl->obj->url = $ctrl->pluginUrl().'/page/task/'.$ctrl->data['id'];
    echo $ctrl
      ->setTitle($ctrl->obj->data['title'])
      ->addJs()
      ->getView();
  }
}
