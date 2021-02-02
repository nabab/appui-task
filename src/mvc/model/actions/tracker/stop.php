<?php
if ( !empty($model->data['id_task']) ){
  $tasks = new \bbn\Appui\Task($model->db);
  return [
    'success' => $tasks->stopTrack($model->data['id_task'], !empty($model->data['message']) ? $model->data['message'] : false),
    'trackers' => $tasks->getTracks($model->data['id_task'])
  ];
}
