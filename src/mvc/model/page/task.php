<?php
/** @var $model \bbn\mvc\model */
if ( !empty($model->data['id']) ){
  $task = new \bbn\appui\tasks($model->db);
  $info = $task->info($model->data['id']);
  $info['notes'] = $model->get_model(APPUI_TASKS_ROOT . 'data/last_messages', ['id_task' => $model->data['id']])['data'];
  return [
    'info' => $info
  ];
}
return [];