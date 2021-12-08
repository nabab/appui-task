<?php

/** @var $ctrl \bbn\Mvc\Controller */
if ( !empty($ctrl->post['id_task']) && !empty($ctrl->post['ref']) ){
  $ctrl->action();
}
