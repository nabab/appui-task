<?php
if ( !empty($model->data['id_user']) ){
  $tasks = new \bbn\Appui\Task($model->db);
  return [
    'success' => true,
    'data' => [
      'active' => $tasks->getActiveTrack($model->data['id_user']),
      'list' => $tasks->getTasksTracks($model->data['id_user'])
    ]
  ];
}
