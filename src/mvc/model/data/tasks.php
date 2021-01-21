<?php
if ( !empty($model->data['id_user']) ){
  $tasks = new \bbn\appui\task($model->db);
  return [
    'success' => true,
    'data' => [
      'active' => $tasks->get_active_track($model->data['id_user']),
      'list' => $tasks->get_tasks_tracks($model->data['id_user'])
    ]
  ];
}
