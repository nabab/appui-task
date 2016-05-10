<?php
/*
 * Describe what it does or you're a pussy
 *
 **/

/** @var $this \bbn\mvc\model*/
if ( isset($this->data['id_task']) ){
	$pm = new \bbn\appui\task($this->db);
  return $pm->get_log($this->data['id_task']);
}
return [];