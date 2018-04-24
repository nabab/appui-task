<?php
/** @var $model \bbn\mvc\model */

if ( !empty($model->data['id']) ){
  $task = new \bbn\appui\tasks($model->db);
  return [
    'info' => $task->info($model->data['id'], true)
  ];
}
return [];