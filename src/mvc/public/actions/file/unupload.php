<?php
/*
 * Describe what it does to show you're not that dumb!
 *
 **/

/** @var $ctrl \bbn\mvc\controller */
$ctrl->obj->success = false;
if ( isset($ctrl->arguments[0], $ctrl->post['filename']) ){
  $path = BBN_USER_PATH.'tmp/'.$ctrl->arguments[0];
  $new = \bbn\str::encode_filename($ctrl->post['filename'], \bbn\str::file_ext($ctrl->post['filename']));
  $file = $path.'/'.$new;
  if ( is_file($file) ){
    $ctrl->obj->success = unlink($file);
  }
}