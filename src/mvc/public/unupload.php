<?php
/*
 * Describe what it does to show you're not that dumb!
 *
 **/

/** @var $this \bbn\mvc\controller */
if ( isset($this->arguments[0], $this->post['fileNames']) ){
  $path = BBN_USER_PATH.'tmp/'.$this->arguments[0];
  $new = \bbn\str::encode_filename($f['name'], \bbn\str::file_ext($f['name']));
  $file = $path.'/'.$new;
  if ( is_file($new) ){
    
  }
  if $this->arguments[0]
}
die(var_dump($this->post));