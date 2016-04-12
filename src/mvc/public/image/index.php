<?php
/*
 * Describe what it does to show you're not that dumb!
 *
 **/

/** @var $this \bbn\mvc\controller */

if ( isset($this->arguments[0]) ){
  $this->set_mode("image");
  $is_tmp = false;
  if ( ($this->arguments[0] === 'tmp') && \bbn\str::is_integer($this->arguments[1]) ){
    array_shift($this->arguments);
    $is_tmp = 1;
	  $path = BBN_USER_PATH.'tmp/'.array_shift($this->arguments).'/';
  }
  if ( isset($path) ){
    $file = $path.implode("/", $this->arguments);
    $this->obj->img = $file;
    //die();
    //die(var_dump($file, is_file($file)));
    //die(var_dump($this->mvc));
  }
}