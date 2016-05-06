<?php
/*
 * Describe what it does or you're a pussy
 *
 **/

/** @var $this \bbn\mvc\model*/
if ( isset($this->data['id']) ){
  $task = new \bbn\appui\task($this->db);
  $r = [
    'info' => $task->info($this->data['id'])
  ];
  if ( !empty($r['info']['notes']) ){
    $note = new \bbn\appui\note($this->db);
    array_walk($r['info']['notes'], function(&$a) use($note){
      $a = $note->get($a);
    });
  }
  return $r;
}
return [];