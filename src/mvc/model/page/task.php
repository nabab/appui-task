<?php
/** @var $model \bbn\Mvc\Model */
if ( !empty($model->data['id']) ){
  $task = new \bbn\Appui\Task($model->db);
  return \bbn\X::mergeArrays(
    $task->info($model->data['id']),
    [
      'root' => APPUI_TASKS_ROOT,
      'notes' => $model->getModel(APPUI_TASKS_ROOT . 'data/last_messages', ['id_task' => $model->data['id']]),
      'approved' => $task->getApprovedLog($model->data['id']),
      'lastChangePrice' => $task->getPriceLog($model->data['id']),
      'tracker' => $task->getTrack($model->data['id']),
      'trackers' => $task->getTracks($model->data['id']),
      'invoice' => $task->getInvoice($model->data['id'])
    ]
  );
}
