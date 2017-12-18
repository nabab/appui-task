<?php
/*
 * Describe what it does to show you're not that dumb!
 *
 **/
/** @var $ctrl \bbn\mvc\controller */
if ( \count($ctrl->arguments) > 1 ){
  switch ( $ctrl->arguments[0] ){
    case 'media':
      $path = BBN_DATA_PATH.'media/'.$ctrl->arguments[1];
      $file = \bbn\file\dir::get_files($path);
      $ctrl->obj->file = $file[0];
      break;
  }
}
