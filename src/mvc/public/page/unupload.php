<?php

/** @var $ctrl \bbn\Mvc\Controller */
$ctrl->obj->success = false;
if ( isset($ctrl->arguments[0], $ctrl->post['fileNames']) ){
  $path = BBN_USER_PATH.'tmp/'.$ctrl->arguments[0];
  $new = \bbn\Str::encodeFilename($ctrl->post['fileNames'], \bbn\Str::fileExt($ctrl->post['fileNames']));
  $file = $path.'/'.$new;
  if ( is_file($file) ){
    $ctrl->obj->files = $ctrl->post['fileNames'];
    $ctrl->obj->success = unlink($file);
  }
}