<?php
/*
 * Describe what it does or you're a pussy
 *
 **/

/** @var $this \bbn\mvc\model*/
if ( isset($this->data['id']) ){
  $pm = new \bbn\appui\task($this->db);
  return [
    'success' => $pm->comment($this->data['id'], [
      'title' => isset($this->data['comment_title']) ? $this->data['comment_title'] : '',
      'text' => isset($this->data['comment']) ? $this->data['comment'] : ''
     ])
  ];
}
