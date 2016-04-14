<?php
/*
 * Describe what it does or you're a pussy
 *
 **/

/** @var $this \bbn\mvc\model */
$pm = new \bbn\appui\task($this->db, $this->inc->user, $this->inc->options);
return ['items' => $pm->get_mine()];