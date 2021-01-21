<?php
if ( !empty($model->data['id_task']) ){
  $tasks = new \bbn\appui\task($model->db);
  return [
    'success' => $tasks->stop_track($model->data['id_task'], !empty($model->data['message']) ? $model->data['message'] : false),
    'trackers' => $tasks->get_tracks($model->data['id_task'])
  ];
}
