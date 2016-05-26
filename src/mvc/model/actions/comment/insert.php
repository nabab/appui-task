<?php
/*
 * Describe what it does or you're a pussy
 *
 **/

/** @var $this \bbn\mvc\model*/
if ( isset($this->data['id']) ){
  $pm = new \bbn\appui\task($this->db);
  $res = $pm->comment($this->data['id'], [
    'files' => !empty($this->data['files']) ? $this->data['files'] : false,
    'title' => isset($this->data['title']) ? $this->data['title'] : '',
    'text' => isset($this->data['text']) ? $this->data['text'] : ''
  ]);
  return [
    'success' => $res,
    'comment' => $pm->get_comment($this->data['id'], $res)
  ];
}
