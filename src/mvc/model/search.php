<?php
/*
 * Describe what it does or you're a pussy
 *
 **/

/** @var $this \bbn\mvc\model*/

if ( isset($this->data['search']) ){
  $pm = new \bbn\appui\task($this->db);
  if ( $rows = $pm->search($this->data['search']) ){
    return ['rows' => $rows];
  }
}
return [];