<?php
/*
 * Describe what it does to show you're not that dumb!
 *
 **/

/** @var $ctrl \bbn\mvc\controller */

if ( isset($ctrl->arguments[0]) ){
  $ctrl->set_mode("image");
  $is_tmp = false;
  if ( ($ctrl->arguments[0] === 'tmp') &&
    \bbn\str::is_uid($ctrl->arguments[1]) &&
    \defined('BBN_USER_PATH')
  ){
    array_shift($ctrl->arguments);
    $is_tmp = 1;
    $path = BBN_USER_PATH.'tmp/'.array_shift($ctrl->arguments).'/';
  }
  else if ( \bbn\str::is_uid($ctrl->arguments[0]) &&
    \defined('BBN_DATA_PATH')
  ){
    $path = BBN_DATA_PATH.'media/'.$ctrl->arguments[0].'/';
    array_shift($ctrl->arguments);
  }
  if ( isset($path) ){
    $file = $path.implode("/", $ctrl->arguments);
    $ctrl->obj->img = $file;
  }
}