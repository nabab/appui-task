<?php
/*
 * Describe what it does or you're a pussy
 *
 **/

/** @var $this \bbn\mvc\model*/
$pm = new \bbn\appui\task($this->db);
return [
  'success' => isset($this->data['id_task'], $this->data['prop'], $this->data['val']) ? $pm->update($this->data['id_task'], $this->data['prop'], $this->data['val']) : false
];