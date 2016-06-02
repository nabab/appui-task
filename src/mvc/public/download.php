<?php
/*
 * Describe what it does to show you're not that dumb!
 *
 **/
/** @var $this \bbn\mvc\controller */
if ( count($this->arguments) > 1 ){
  switch ( $this->arguments[0] ){
    case 'media':
      $path = BBN_DATA_PATH.'media/'.$this->arguments[1];
      $file = \bbn\file\dir::get_files($path);
      $this->obj->file = $file[0];
      break;
  }
}
