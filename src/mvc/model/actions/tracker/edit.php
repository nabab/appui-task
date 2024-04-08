<?php

if (!empty($model->data['id'])
  && !empty($model->data['id_task'])
  && !empty($model->data['start'])
  && !empty($model->data['end'])
  && (strtotime($model->data['end']) > strtotime($model->data['start']))
){
  $success = true;
  $taskCls = new \bbn\Appui\Task($model->db);
  $currentTrack = $taskCls->getTrack($model->data['id']);
  $note = $taskCls->getTrackNote($model->data['id']);
  $message = !empty($note['content']) && !empty($note['content']) ? $note['content'] : '';
  if (($currentTrack['start'] !== $model->data['start'])
    || ($currentTrack['end'] !== $model->data['end'])
    || ($message !== $model->data['message'])
   ) {
     $success = $taskCls->editTrack(
       $model->data['id'],
       $model->data['start'],
       $model->data['end'],
       $model->data['message'] ?? null
     );
  }

  if ($currentTrack['id_task'] !== $model->data['id_task']) {
    $success = $taskCls->moveTrack($model->data['id'], $model->data['id_task']);
  }

  $note = $taskCls->getTrackNote($model->data['id']);
  $message = !empty($note['content']) && !empty($note['content']) ? $note['content'] : '';
  return [
    'success' => $success,
    'data' => \bbn\X::mergeArrays(
      $taskCls->getTrack($model->data['id']),
      ['message' => $message]
    )
  ];
}

return ['success' => false];
