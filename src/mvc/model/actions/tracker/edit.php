<?php

if (!empty($model->data['id'])
  && !empty($model->data['start'])
  && !empty($model->data['end'])
  && (strtotime($model->data['end']) > strtotime($model->data['start']))
){
  $taskCls = new \bbn\Appui\Task($model->db);
  return [
    'success' => $taskCls->editTracker(
      $model->data['id'],
      $model->data['start'],
      $model->data['end'],
      $model->data['message'] ?? null
    )
  ];
}

return ['success' => false];
