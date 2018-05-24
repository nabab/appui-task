<?php
/*
 * Describe what it does to show you're not that dumb!
 *
 **/

/** @var $ctrl \bbn\mvc\controller */
if ( !empty($ctrl->post['id_task']) && !empty($ctrl->post['ref']) ){
  $ctrl->action();
}
