<?php
/*
 * Describe what it does or you're a pussy
 *
 **/

/** @var $this \bbn\mvc\model*/
$pm = new \bbn\appui\task($this->db);
return [
  'success' => isset($this->data['title'], $this->data['type']) ? $pm->insert([
    'title' => $this->data['title'],
    'type' => $this->data['type'],
    'deadline' => empty($this->data['deadline']) ? null : $this->data['deadline']
   ]) : false
];
