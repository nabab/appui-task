<?php
/*
 * Describe what it does or you're a pussy
 *
 **/

/** @var $this \bbn\mvc\model*/
$pm = new \bbn\appui\task($this->db);
return [
  'success' => isset($this->data['title'], $this->data['type']) ? $pm->insert($this->data['title'], $this->data['type']) : false
];