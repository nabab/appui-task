<?php

/** @var bbn\Mvc\Controller $ctrl */
if ( !empty($ctrl->post['id_task']) && !empty($ctrl->post['ref']) ){
  $ctrl->action();
}
