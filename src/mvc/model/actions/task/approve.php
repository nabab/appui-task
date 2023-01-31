<?php
if ( !empty($model->data['id_task']) ){
  $tasks = new \bbn\Appui\Task($model->db);
  return [
    'success' => $tasks->approve($model->data['id_task']),
    'approved' => $tasks->getApprovedLog($model->data['id_task'])
  ];
}
