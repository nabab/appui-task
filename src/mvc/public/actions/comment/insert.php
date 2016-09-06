<?php
/*
 * Describe what it does to show you're not that dumb!
 *
 **/

/** @var $ctrl \bbn\mvc\controller */
if ( isset($ctrl->post['ref']) ){
  $path = BBN_USER_PATH.'tmp/'.$ctrl->post['ref'];
  $files = \bbn\file\dir::get_files($path);
  $ctrl->post['files'] = $files;
}
$ctrl->obj = $ctrl->get_object_model($ctrl->post);