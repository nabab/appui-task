<?php
/*
 * Describe what it does or you're a pussy
 *
 **/

/** @var $this \bbn\mvc\model*/
if ( isset($this->data['id']) ){
  $task = new \bbn\appui\task($this->db);
  return ['info' => $task->info($this->data['id'])];
}
return [];