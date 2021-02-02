<?php
/*
 * Describe what it does to show you're not that dumb!
 *
 **/

/** @var $ctrl \bbn\Mvc\Controller */
if ( !empty($ctrl->post['id_task']) && !empty($ctrl->post['ref']) ){
  $ctrl->action();
}
