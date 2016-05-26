<?php
/*
 * Describe what it does to show you're not that dumb!
 *
 **/

/** @var $this \bbn\mvc\controller */
if ( isset($this->post['ref']) ){
  $path = BBN_USER_PATH.'tmp/'.$this->post['ref'];
  $files = \bbn\file\dir::get_files($path);
  $this->post['files'] = $files;
}
$this->obj = $this->get_object_model($this->post);