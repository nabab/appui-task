<?php
/*
 * Describe what it does to show you're not that dumb!
 *
 **/

/** @var $ctrl bbn\mvc\controller */
if ( isset($ctrl->post['url'], $ctrl->post['ref']) ){
  $ctrl->obj = $ctrl->get_model($ctrl->post);
}