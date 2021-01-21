<?php
/** @var $model \bbn\mvc\model */

$model->data['limit'] = isset($model->data['limit']) && is_int($model->data['limit']) ? $model->data['limit'] : 5;
$model->data['start'] = isset($model->data['start']) && is_int($model->data['start']) ? $model->data['start'] : 0;
$closed_state = $model->inc->options->from_root_code('closed', 'states', 'task', 'appui');
$id_user = $model->inc->user->get_id();
$grid = new \bbn\appui\grid($model->db, $model->data, [
  'tables' => ['bbn_tasks'],
  'fields' => [
    'bbn_tasks.id',
    'title',
    'status' => 'bbn_options.code',
    'bugclass' => 'bbn_options.code',
    'priority',
    'last_activity' => "FROM_UNIXTIME(MAX(bbn_tasks_logs.chrono), '%Y-%m-%d %H:%i:%s')"
  ],
  'join' => [[
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
  return $grid->get_datatable();
}
