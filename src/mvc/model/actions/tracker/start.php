<?php
if ( !empty($model->data['id_task']) ){
  $tasks = new \bbn\appui\tasks($model->db);
  return [
    'success' => $tasks->start_track($model->data['id_task']),
    'tracker' => $tasks->get_track($model->data['id_task'])
  ];
}
