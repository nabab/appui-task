<?php
if ( !empty($model->data['id_task']) ){
  $tasks = new \bbn\Appui\Task($model->db);
  return [
    'success' => $tasks->startTrack($model->data['id_task']),
    'tracker' => $tasks->getActiveTrack(false, $model->data['id_task'])
  ];
}
