<?php

use bbn\Str;

/** @var bbn\Mvc\Controller $ctrl */

if ( isset($ctrl->arguments[0]) ){
  $ctrl->setMode("image");
  $is_tmp = false;
  if ( ($ctrl->arguments[0] === 'tmp') &&
    Str::isInteger($ctrl->arguments[1]) &&
    $ctrl->inc->user
  ){
    array_shift($ctrl->arguments);
    $is_tmp = 1;
    $path = $ctrl->userTmpPath() . array_shift($ctrl->arguments).'/';
  }
  elseif (Str::isUid($ctrl->arguments[0])){
    $path = $ctrl->userTmpPath().'media/'.$ctrl->arguments[0].'/';
    array_shift($ctrl->arguments);
  }
  if ( isset($path) ){
    $file = $path.implode("/", $ctrl->arguments);
    $ctrl->obj->img = $file;
  }
}
