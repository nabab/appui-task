<?php
if ( !empty($model->data['id_task']) ){
  $tasks = new \bbn\appui\tasks($model->db);
  return [
    'success' => $tasks->stop_track($model->data['id_task'], $model->data['message'] ?: false),
    'trackers' => $tasks->get_tracks($model->data['id_task'])
  ];
}