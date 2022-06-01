<?php

if ($model->hasData(['id', 'code'], true)) {
  $task = new \bbn\Appui\Task($model->db);
  return [
    'success' => $task->addWidget($model->data['id'], $model->data['code'])
  ];
}
return ['success' => false];