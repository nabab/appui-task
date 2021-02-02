<?php
/*
 * Describe what it does or you're a pussy
 *
 **/

/** @var $model \bbn\Mvc\Model*/
$res = [];
$pm = new \bbn\Appui\Task($model->db);
$id_user = $model->inc->user->getId();
$id_group = $model->inc->user->getGroup();
$state_closed = $pm->idState('closed');

if ( !empty($model->data['data']) ){
  $ext_filters = $model->data['data'];
  unset($model->data['data']);
}

$fields = [
  'bbn_tasks.id',
  'bbn_tasks.type',
  'bbn_tasks.id_parent',
  'bbn_tasks.id_user',
  'bbn_tasks.title',
  'bbn_tasks.state',
  'bbn_tasks.priority',
  'bbn_tasks.id_alias',
  'bbn_tasks.creation_date',
  'bbn_tasks.deadline',
  'bbn_tasks.price',
  'bbn_tasks.private',
  'bbn_tasks.active',
  'role' => 'bbn_tasks_roles.role',
  'last_action' => 'FROM_UNIXTIME(MAX(bbn_tasks_logs.chrono))',
  'num_children' => 'COUNT(children.id)',
  'num_notes' => 'COUNT(DISTINCT bbn_tasks_notes.id_note)',
  'duration' => "IF(bbn_tasks.`state` = UNHEX('$state_closed'), MAX(bbn_tasks_logs.chrono), UNIX_TIMESTAMP()) - MIN(bbn_tasks_logs.chrono)"
];

$join = [[
  'table' => 'bbn_tasks_roles',
  'type' => 'left',
  'alias' => 'my_role',
  'on' => [
    'conditions' => [[
      'field' => 'my_role.id_task',
      'exp' => 'bbn_tasks.id'
    ], [
      'field' => 'my_role.id_user',
      'value' => $id_user
    ]]
  ]
], [
  'table' => 'bbn_tasks_roles',
  'type' => 'left',
  'on' => [
    'conditions' => [[
      'field' => 'bbn_tasks_roles.id_task',
      'exp' => 'bbn_tasks.id'
    ]]
  ]
], [
  'table' => 'bbn_tasks_logs',
  'on' => [
    'conditions' => [[
      'field' => 'bbn_tasks_logs.id_task',
      'exp' => 'bbn_tasks_roles.id_task'
    ]]
  ]
], [
  'table' => 'bbn_tasks_notes',
  'type' => 'left',
  'on' => [
    'conditions' => [[
      'field' => 'bbn_tasks_notes.id_task',
      'exp' => 'bbn_tasks.id'
    ]]
  ]
], [
  'table' => 'bbn_tasks',
  'type' => 'left',
  'alias' => 'children',
  'on' => [
    'conditions' => [[
      'field' => 'bbn_tasks_roles.id_task',
      'exp' => 'children.id_parent'
    ]]
  ]
]];

$filters = [
  'conditions' => [[
    'field' => 'bbn_tasks.active',
    'value' => 1
  ]]
];

if ( isset($ext_filters['selection']) ){
  if ( $ext_filters['selection'] === 'user' ){
    $join[] = [
      'table' => 'bbn_tasks_roles',
      'alias' => 'user_role',
      'on' => [
        'conditions' => [[
          'field' => 'bbn_tasks.id',
          'exp' => 'user_role.id_task'
        ]]
      ]
    ];
    $filters['conditions'][] = [
      'field' => 'user_role.id_user',
      'value' => $id_user
    ];
  }
  else if ( $ext_filters['selection'] === 'group' ){
    $join[] = [
      'table' => 'bbn_tasks_roles',
      'alias' => 'group_role',
      'on' => [
        'conditions' => [[
          'field' => 'bbn_tasks.id',
          'exp' => 'group_role.id_task'
        ]]
      ]
    ];
    $join[] = [
      'table' => 'bbn_users',
      'on' => [
        'conditions' => [[
          'field' => 'bbn_tasks_roles.id_user',
          'exp' => 'bbn_users.id'
        ]]
      ]
    ];
    $filters['conditions'][] = [
      'field' => 'bbn_users.id_group',
      'value' => $id_group
    ];
  }
}

if ( !empty($ext_filters['title']) ){
  $filters['conditions'][] = [
    'field' => 'bbn_tasks.title',
    'operator' => 'contains',
    'value' => $ext_filters['title']
  ];
}

if ( $plugin_model = $model->getPluginModel('reference') ){
  if ( !empty($plugin_model['fields']) ){
    $fields = \bbn\X::mergeArrays($fields, $plugin_model['fields']);
  }
  if ( !empty($plugin_model['join']) ){
    $join = \bbn\X::mergeArrays($join, $plugin_model['join']);
  }
  if ( !empty($plugin_model['filters']) ){
    $filters = \bbn\X::mergeArrays($filters, $plugin_model['filters']);
  }
}


$grid = new \bbn\Appui\Grid($model->db, $model->data, [
  'table' => 'bbn_tasks',
  'fields' => $fields,
  'join' => $join,
  'filters' => $filters,
  'group_by' => ['bbn_tasks.id']
]);

if ( $grid->check() ){
  $data = $grid->getDatatable();
  if ( !empty($data['data']) && !empty($plugin_model['template']) && is_callable($plugin_model['template']) ){
    foreach ( $data['data'] as $i => $d ){
      if ( !empty($d['reference']) ){
        $data['data'][$i]['reference'] = $plugin_model['template']($model->db, $d);
      }
    }
  }
  return $data;
}