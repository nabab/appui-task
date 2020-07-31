<?php
$model->data['limit'] = isset($model->data['limit']) && is_int($model->data['limit']) ? $model->data['limit'] : 5;
$model->data['start'] = isset($model->data['start']) && is_int($model->data['start']) ? $model->data['start'] : 0;
$closed_state = $model->inc->options->from_root_code('closed', 'states', 'tasks', 'appui');
$executant = $model->inc->options->from_root_code('workers', 'roles', 'tasks', 'appui');
$grid = new \bbn\appui\grid($model->db, $model->data, [
  'table' => 'bbn_tasks',
  'fields' => [
    'bbn_tasks.id',
    'title',
    'status' => 'bbn_options.code',
    'bugclass' => 'bbn_options.code',
    'priority',
    'last_activity' => 'FROM_UNIXTIME(MAX(bbn_tasks_logs.chrono), \'%Y-%m-%d %H:%i:%s\')'
  ],
  'join' => [[
    'table' => 'bbn_tasks_logs',
    'on' => [
      'conditions' => [[
        'field' => 'bbn_tasks.id',
        'operator' => 'eq',
        'exp' => 'bbn_tasks_logs.id_task'
      ]]
    ]
  ], [
    'table' => 'bbn_options',
    'on' => [
      'conditions' => [[
        'field' => 'bbn_tasks.state',
        'operator' => 'eq',
        'exp' => 'bbn_options.id'
      ]]
    ]
  ], [
    'table' => 'bbn_tasks_roles',
    'type' => 'left',
    'on' => [
      'conditions' => [[
        'field' => 'bbn_tasks.id',
        'operator' => 'eq',
        'exp' => 'bbn_tasks_roles.id_task'
      ], [
        'field' =>  'bbn_tasks_roles.role',
        'operator' => 'eq',
        'value' => $executant
      ]]
    ]
  ]],
  'order' => [[
    'field' => 'last_activity',
    'dir' => 'DESC'
  ]],
  'group_by' => 'bbn_tasks.id',
  'filters' => [
    'conditions' => [[
      'field' => 'state',
      'operator' => 'neq',
      'value' => $closed_state
    ],[
      'field' => 'bbn_tasks_roles.role',
      'operator' => 'isnull'
    ]]
  ],
  'observer' => [
    'request' => [
      'table' => 'bbn_tasks',
      'fields' =>['chrono'],
      'join' => [[
        'table' => 'bbn_tasks_logs',
        'on' => [
          'conditions' => [[
            'field' => 'bbn_tasks.id',
            'exp' => 'bbn_tasks_logs.id_task'
          ]]
        ]
      ], [
        'table' => 'bbn_options',
        'on' => [
          'conditions' => [[
            'field' => 'bbn_tasks.state',
            'exp' => 'bbn_options.id'
          ]]
        ]
      ], [
        'table' => 'bbn_tasks_roles',
        'type' => 'left',
        'on' => [
          'conditions' => [[
            'field' => 'bbn_tasks.id',
            'operator' => 'eq',
            'exp' => 'bbn_tasks_roles.id_task'
          ], [
            'field' =>  'bbn_tasks_roles.role',
            'operator' => 'eq',
            'value' => $executant
          ]]
        ]
      ]],
      'order' => [[
        'field' => 'chrono',
        'dir' => 'DESC'
      ]],
      'where' => [
        'conditions' => [[
          'field' => 'state',
          'operator' => 'neq',
          'value' => $closed_state
        ],[
          'field' => 'bbn_tasks_roles.role',
          'operator' => 'isnull'
        ]]
      ],
    ],
    'name' => _('Latest tasks'),
    'public' => true
  ]
]);
if ( $grid->check() ){
  return $grid->get_datatable(true);
}
