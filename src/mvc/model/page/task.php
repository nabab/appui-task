<?php
/** @var $model \bbn\mvc\model */
if ( !empty($model->data['id']) ){
  $task = new \bbn\appui\task($model->db);
  return \bbn\x::merge_arrays(
    $task->info($model->data['id']),
    [
      'root' => APPUI_TASKS_ROOT,
      'notes' => $model->get_model(APPUI_TASKS_ROOT . 'data/last_messages', ['id_task' => $model->data['id']]),
      'approved' => $task->get_approved_log($model->data['id']),
      'lastChangePrice' => $task->get_price_log($model->data['id']),
      'tracker' => $task->get_track($model->data['id']),
      'trackers' => $task->get_tracks($model->data['id']),
      'invoice' => $task->get_invoice($model->data['id'])
    ]
  );
}
