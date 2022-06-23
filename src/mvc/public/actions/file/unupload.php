<?php
/*
 * Describe what it does to show you're not that dumb!
 *
 **/

/** @var $ctrl \bbn\Mvc\Controller */
if (!empty($ctrl->arguments[0])
  && !empty($ctrl->post['file'])
){
  $path = $ctrl->userTmpPath() . $ctrl->arguments[0];
  $new = \bbn\Str::encodeFilename($ctrl->post['file'], \bbn\Str::fileExt($ctrl->post['file']));
  $file = $path . '/' . $new;
  if (is_file($file) && unlink($file)) {
    $ctrl->obj->success = true;
  }
  else {
    $ctrl->obj->success = false;
  }
}