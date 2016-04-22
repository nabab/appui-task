<?php
/*
 * Describe what it does or you're a pussy
 *
 **/

/** @var $this \bbn\mvc\model*/
$pm = new \bbn\appui\task($this->db);
return $pm->get_mine(empty($this->data['id']) ? null : $this->data['id']);
