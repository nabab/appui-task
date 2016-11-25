<?php
/*
 * Describe what it does or you're a pussy
 *
 **/

/** @var $model \bbn\mvc\model*/
if ( isset($model->data['id']) ){
  $pm = new \bbn\appui\tasks($model->db);
  $res = $pm->comment($model->data['id'], [
    'files' => !empty($model->data['files']) ? $model->data['files'] : false,
    'title' => isset($model->data['title']) ? $model->data['title'] : '',
    'text' => isset($model->data['text']) ? $model->data['text'] : ''
  ]);
  return [
    'success' => $res,
    'comment' => $pm->get_comment($model->data['id'], $res)
  ];
}
