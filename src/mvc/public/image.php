<?php
/*
 * Describe what it does to show you're not that dumb!
 *
 **/

/** @var $ctrl \bbn\Mvc\Controller */

if ( isset($ctrl->arguments[0]) ){
  $ctrl->setMode("image");
  $is_tmp = false;
  if ( ($ctrl->arguments[0] === 'tmp') && \bbn\Str::isInteger($ctrl->arguments[1]) ){
    array_shift($ctrl->arguments);
    $is_tmp = 1;
	  $path = BBN_USER_PATH.'tmp/'.array_shift($ctrl->arguments).'/';
  }
  if ( isset($path) ){
    $file = $path.implode("/", $ctrl->arguments);
    $ctrl->obj->img = $file;
    //die();
    //die(var_dump($file, is_file($file)));
    //die(var_dump($ctrl->mvc));
  }
}