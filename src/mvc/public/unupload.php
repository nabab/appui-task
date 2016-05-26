<?php
/*
 * Describe what it does to show you're not that dumb!
 *
 **/

/** @var $this \bbn\mvc\controller */
$this->obj->success = false;
if ( isset($this->arguments[0], $this->post['fileNames']) ){
  $path = BBN_USER_PATH.'tmp/'.$this->arguments[0];
  $new = \bbn\str::encode_filename($this->post['fileNames'], \bbn\str::file_ext($this->post['fileNames']));
  $file = $path.'/'.$new;
  if ( is_file($file) ){
    $this->obj->files = $this->post['fileNames'];
    $this->obj->success = unlink($file);
  }
}