<?php
/*
 * Describe what it does to show you're not that dumb!
 *
 **/

/** @var $ctrl \bbn\mvc\controller */
if ( !empty($ctrl->post['id'] && $ctrl->post['ref']) ){
  $ctrl->data = $ctrl->post;
  $ctrl->obj = $ctrl->get_object_model($ctrl->data);
}
