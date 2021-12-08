<?php

/** @var $ctrl bbn\Mvc\Controller */
if ( isset($ctrl->post['url'], $ctrl->post['ref']) ){
  $ctrl->obj = $ctrl->getModel($ctrl->post);
}