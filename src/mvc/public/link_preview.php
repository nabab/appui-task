<?php

/** @var bbn\Mvc\Controller $ctrl */
if ( isset($ctrl->post['url'], $ctrl->post['ref']) ){
  $ctrl->obj = $ctrl->getModel($ctrl->post);
}