<?php
/** @var bbn\Mvc\Model $model */

$model->data['limit'] = isset($model->data['limit']) && is_int($model->data['limit']) ? $model->data['limit'] : 5;
$model->data['start'] = isset($model->data['start']) && is_int($model->data['start']) ? $model->data['start'] : 0;
$closed_state = $model->inc->options->fromCode('closed', 'states', 'task', 'appui');
$id_user = $model->inc->user->getId();
$grid = new \bbn\Appui\Grid($model->db, $model->data, [
  'tables' => ['bbn_tasks'],
  'fields' => [
    'bbn_tasks.id',
    'bbn_notes_versions.title',
    'bbn_notes_versions.content',
    'status' => 'bbn_options.code',
    'bugclass' => 'bbn_options.code',
    'priority',
    'last_activity' => "FROM_UNIXTIME(MAX(bbn_tasks_logs.chrono), '%Y-%m-%d %H:%i:%s')"
  ],
  'join' => [[
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
  ], [
    'table' => 'bbn_tasks_roles',
    'on' => [
      'logic' => 'AND',
      'conditions' => [[
        'field' => 'bbn_tasks.id',
        'operator' => '=',
        'exp' => 'bbn_tasks_roles.id_task'
      ],[
        'field' =>  'bbn_tasks_roles.role',
        'operator' => '!=',
        'value' => $closed_state
      ]]
    ]
  ], [
    'table' => 'bbn_tasks_logs',
    'on' => [
      'logic' => 'AND',
      'conditions' => [[
        'field' => 'bbn_tasks.id',
        'operator' => '=',
        'exp' => 'bbn_tasks_logs.id_task'
      ]]
    ]
  ], [
    'table' => 'bbn_options',
    'on' => [
      'logic' => 'AND',
      'conditions' => [[
        'field' => 'bbn_tasks.state',
        'operator' => '=',
        'exp' => 'bbn_options.id'
      ]]]
  ]],
  'order' => [[
    'field' => 'last_activity',
    'dir' => 'DESC'
  ]],
  'group_by' => ['bbn_tasks.id'],
  'filters' => [[
    'field' => 'bbn_tasks_roles.id_user',
    'operator' => '=',
    'value' => $id_user
  ]],
  'observer' => [
    'request' => "
			SELECT bbn_tasks_logs.chrono
      FROM bbn_tasks
        JOIN bbn_tasks_roles
          ON bbn_tasks.id = bbn_tasks_roles.id_task
        JOIN bbn_tasks_logs
          ON bbn_tasks.id = bbn_tasks_logs.id_task
        JOIN bbn_options
          ON bbn_tasks.state = bbn_options.id
      WHERE bbn_tasks.state != ?
        AND bbn_tasks_roles.id_user = ?
      ORDER BY chrono DESC
      LIMIT 1",
    'params' => [hex2bin($closed_state), hex2bin($id_user)],
    'name' => _('My current tasks'),
    'public' => false
  ]
]);
if ( $grid->check() ) {
  return $grid->getDatatable();
}
