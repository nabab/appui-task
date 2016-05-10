<?php
/*
 * Describe what it does or you're a pussy
 *
 **/

/** @var $this \bbn\mvc\model*/
$pm = new \bbn\appui\task($this->db);
if ( !empty($this->data['search']) ){
  return $pm->get_slist($this->data['search']);
}
else{
  return $pm->get_mine(isset($this->data['id']) ? $this->data['id'] : null);
}

