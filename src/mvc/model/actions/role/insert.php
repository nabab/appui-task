<?php
/*
 * Describe what it does or you're a pussy
 *
 **/

/** @var $this \bbn\mvc\model*/

$res = false;
if ( isset($this->data['id_task'], $this->data['role'], $this->data['id_user']) ){
	$pm = new \bbn\appui\task($this->db);
  $res = $pm->add_role($this->data['id_task'], $this->data['role'], $this->data['id_user']);
}
return ['success' => $res];