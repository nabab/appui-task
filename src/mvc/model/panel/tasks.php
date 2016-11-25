<?php
/*
 * Describe what it does or you're a pussy
 *
 **/

/** @var $model \bbn\mvc\model*/
if ( isset($model->data['id']) ){
  $task = new \bbn\appui\tasks($model->db);
  return [
    'info' => $task->info($model->data['id'], true)
  ];
}
return [];