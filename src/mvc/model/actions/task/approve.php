<?php
if ( !empty($model->data['id_task']) ){
  $tasks = new \bbn\appui\task($model->db);
  return [
    'success' => $tasks->approve($model->data['id_task']),
    'data' => [
        'approved' => $tasks->get_approved_log($model->data['id_task'])
    ]
  ];
}
