<?php
/*
 * Describe what it does or you're a pussy
 *
 **/

/** @var $this \bbn\mvc\model*/
if ( isset($this->data['id']) ){
  $task = new \bbn\appui\task($this->db);
  //$statuses = empty($this->data['closed']) ? 'opened|ongoing|holding' : false;
  return $task->get_tree($this->data['id']);
}
return [];
