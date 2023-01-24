<?php
if ($model->hasData('start')) {
  $grid = new \bbn\Appui\Grid($model->db, $model->data, [
    'table' => 'bbn_tasks_logs',
    'fields' => \bbn\X::mergeArrays($model->db->getFieldsList('bbn_tasks_logs'), [
      'title' => 'bbn_notes_versions.title',
      'state' => 'bbn_tasks.state'
    ]),
    'join' => [[
      'table' => 'bbn_tasks',
      'on' => [
        'conditions' => [[
          'field' => 'bbn_tasks.id',
          'exp' => 'bbn_tasks_logs.id_task'
        ], [
          'field' => 'bbn_tasks.active',
          'value' => 1
        ]]
      ]
    ], [
      'table' => 'bbn_users',
      'on' => [
        'conditions' => [[
          'field' => 'bbn_users.id',
          'exp' => 'bbn_tasks_logs.id_user'
        ]]
      ]
    ], [
      'table' => 'bbn_options',
      'on' => [
        'conditions' => [[
          'field' => 'bbn_options.id',
          'exp' => 'bbn_tasks_logs.action'
        ]]
      ]
    ], [
      'table' => 'bbn_notes_versions',
      'on' => [
        'conditions' => [[
          'field' => 'bbn_notes_versions.id_note',
          'exp' => 'bbn_tasks.id_note'
        ], [
          'field' => 'bbn_notes_versions.latest',
          'value' => 1
        ]]
      ]
    ]],
    'order' => ['bbn_tasks_logs.chrono' => 'desc']
  ]);
  if ($grid->check()) {
    $data = $grid->getDatatable();
    $taskCls = new \bbn\Appui\Task($model->db);
    foreach ($data['data'] as $i => $d) {
      $data['data'][$i]['log'] = $taskCls->translateLog($d);
    }
    return $data;
  }
}
