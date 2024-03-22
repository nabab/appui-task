<?php
/** @var $model \bbn\Mvc\Model */
if ($model->hasData('id', true)) {
  $task = new \bbn\Appui\Task($model->db);
  if ($task->exists($model->data['id'])
    && !$task->isDeleted($model->data['id'])
  ) {
    $info = $task->info($model->data['id']);
    $logs = $task->getLog($model->data['id']);
    $lastChangePrice = $task->getPriceLog($model->data['id']) ?: null;
    $docs = $model->db->rselectAll([
      'table' => 'bbn_tasks_notes',
      'fields' => [
        'bbn_medias.id',
        'bbn_medias.name'
      ],
      'join' => [[
        'table' => 'bbn_tasks',
        'on' => [
          'conditions' => [[
            'field' => 'bbn_tasks.id',
            'exp' => 'bbn_tasks_notes.id_task'
          ]]
        ]
      ], [
        'table' => 'bbn_notes',
        'on' => [
          'conditions' => [[
            'field' => 'bbn_notes.active',
            'value' => 1
          ], [
            'logic' => 'OR',
            'conditions' => [[
              'field' => 'bbn_notes.id',
              'exp' => 'bbn_tasks_notes.id_note'
            ], [
              'field' => 'bbn_notes.id_alias',
              'exp' => 'bbn_tasks_notes.id_note'
            ]]
          ]]
        ]
      ], [
        'table' => 'bbn_notes_medias',
        'on' => [
          'conditions' => [[
            'field' => 'bbn_notes_medias.id_note',
            'exp' => 'bbn_notes.id'
          ]]
        ]
      ], [
        'table' => 'bbn_medias',
        'on' => [
          'conditions' => [[
            'field' => 'bbn_medias.id',
            'exp' => 'bbn_notes_medias.id_media'
          ]]
        ]
      ]],
      'where' => [
        'conditions' => [[
          'field' => 'bbn_tasks_notes.id_task',
          'value' => $model->data['id']
        ], [
          'field' => 'bbn_tasks_notes.active',
          'value' => 1
        ]]
      ]
    ]);
    return \bbn\X::mergeArrays(
      $info,
      [
        'root' => APPUI_TASKS_ROOT,
        //'notes' => $model->getModel(APPUI_TASKS_ROOT . 'data/last_messages', ['id_task' => $model->data['id']]),
        'approved' => $task->getApprovalInfo($model->data['id']),
        'lastChangePrice' => $lastChangePrice,
        'tracker' => $task->getActiveTrack(false, $model->data['id']),
        'trackers' => $task->getTracks($model->data['id']),
        'invoice' => $task->getInvoice($model->data['id']),
        'lastLogs' => !empty($logs) ? array_slice($logs, 0, 5) : [],
        'totLogs' => count($logs),
        'documents' => $docs,
        'idRoot' => $task->getIdRoot($model->data['id'])
      ]
    );
  }
  return ['error' => _('Non-existent task')];
}
