<?php

if (!empty($model->data['id'])
  && !empty($model->data['start'])
  && !empty($model->data['end'])
  && (strtotime($model->data['end']) > strtotime($model->data['start']))
){
  $taskCls = new \bbn\Appui\Task($model->db);
  $success = $taskCls->editTrack(
    $model->data['id'],
    $model->data['start'],
    $model->data['end'],
    $model->data['message'] ?? null
  );
  $note = $taskCls->getTrackNote($model->data['id']);
  return [
    'success' => $success,
    'data' => \bbn\X::mergeArrays(
      $taskCls->getTrack($model->data['id']),
      ['message' => !empty($note['content']) && !empty($note['content']) ? $note['content'] : '']
    )
  ];
}

return ['success' => false];
