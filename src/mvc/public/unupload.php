<?php
/*
 * Describe what it does to show you're not that dumb!
 *
 **/

/** @var $ctrl \bbn\mvc\controller */
$ctrl->obj->success = false;
if ( isset($ctrl->arguments[0], $ctrl->post['fileNames']) ){
  $path = BBN_USER_PATH.'tmp/'.$ctrl->arguments[0];
  $new = \bbn\str::encode_filename($ctrl->post['fileNames'], \bbn\str::file_ext($ctrl->post['fileNames']));
  $file = $path.'/'.$new;
  if ( is_file($file) ){
    $ctrl->obj->files = $ctrl->post['fileNames'];
    $ctrl->obj->success = unlink($file);
  }
}