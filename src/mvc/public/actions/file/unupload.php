<?php
/*
 * Describe what it does to show you're not that dumb!
 *
 **/

/** @var $ctrl \bbn\mvc\controller */
if ( isset($ctrl->arguments[0], $ctrl->post['file']) ){
  $path = BBN_USER_PATH.'tmp/'.$ctrl->arguments[0];
  $new = \bbn\str::encode_filename($ctrl->post['file'], \bbn\str::file_ext($ctrl->post['file']));
  $file = $path.'/'.$new;
  if ( is_file($file) && unlink($file) ){
    $ctrl->obj->success = true;
  }
  else {
    $ctrl->obj->success = false;
  }
}