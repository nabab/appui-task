<?php
/*
 * Describe what it does or you're a pussy
 *
 **/

/** @var bbn\Mvc\Model $model */
$res = [];
$taskCls = new \bbn\Appui\Task($model->db);
$id_user = $model->inc->user->getId();
$id_group = $model->inc->user->getGroup();
$state_closed = $taskCls->idState('closed');

//Privileges
$privileges = $model->inc->options->codeIds('privileges', 'task', 'appui');
$isAdmin = $model->inc->user->isAdmin();
$isDev = $model->inc->user->isDev();
$isGlobal = !empty($privileges['global'])
  && $model->inc->perm->has($model->inc->perm->optionToPermission($privileges['global'], true));
$allPrivileges = !!$isAdmin || !!$isDev || !!$isGlobal;
$isAccountManager = !$allPrivileges
  && !empty($privileges['account_manager'])
  && $model->inc->perm->has($model->inc->perm->optionToPermission($privileges['account_manager'], true));
$isAccountViewer = !$allPrivileges
  && !empty($privileges['account_viewer'])
  && $model->inc->perm->has($model->inc->perm->optionToPermission($privileges['account_viewer'], true));
$isProjectManager = !$allPrivileges
  && !empty($privileges['project_manager'])
  && $model->inc->perm->has($model->inc->perm->optionToPermission($privileges['project_manager'], true));
$isProjectViewer = !$allPrivileges
  && !empty($privileges['project_viewer'])
  && $model->inc->perm->has($model->inc->perm->optionToPermission($privileges['project_viewer'], true));
$isAssigner = !$allPrivileges
  && !empty($privileges['assigner'])
  && $model->inc->perm->has($model->inc->perm->optionToPermission($privileges['assigner'], true));
$isFinancialManager = !$allPrivileges
  && !empty($privileges['financial_manager'])
  && $model->inc->perm->has($model->inc->perm->optionToPermission($privileges['financial_manager'], true));
$isFinancialViewer = !$allPrivileges
  && !empty($privileges['financial_viewer'])
  && $model->inc->perm->has($model->inc->perm->optionToPermission($privileges['financial_viewer'], true));
$isProjectSupervisor = !$allPrivileges
  && !empty($privileges['project_supervisor'])
  && $model->inc->perm->has($model->inc->perm->optionToPermission($privileges['project_supervisor'], true));

if ( !empty($model->data['data']) ){
  $ext_filters = $model->data['data'];
  unset($model->data['data']);
}

$fields = [
  'bbn_tasks.id',
  'bbn_tasks.ref',
  'bbn_tasks.type',
  'bbn_tasks.id_parent',
  'bbn_tasks.id_user',
  'bbn_notes_versions.title',
  'bbn_notes_versions.content',
  'bbn_tasks.state',
  'bbn_tasks.priority',
  'bbn_tasks.id_alias',
  'bbn_tasks.creation_date',
  'bbn_tasks.deadline',
  'bbn_tasks.price',
  'bbn_tasks.private',
  'bbn_tasks.active',
  'role' => 'my_role.role',
  'last' => 'MAX(bbn_tasks_logs.chrono)',
  'last_action' => 'FROM_UNIXTIME(MAX(bbn_tasks_logs.chrono), "%Y-%m-%d %H:%i:%s")',
  'num_notes' => 'COUNT(DISTINCT bbn_tasks_notes.id_note)',
  'duration' => "IF(bbn_tasks.`state` = UNHEX('$state_closed'), MAX(bbn_tasks_logs.chrono), UNIX_TIMESTAMP()) - MIN(bbn_tasks_logs.chrono)"
];

$join = [[
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
  'alias' => 'parent',
  'type' => 'left',
  'on' => [
    'conditions' => [[
      'field' => 'bbn_tasks.id_parent',
      'exp' => 'parent.id'
    ]]
  ]
], [
  'table' => 'bbn_tasks',
  'alias' => 'children',
  'type' => 'left',
  'on' => [
    'conditions' => [[
      'field' => 'bbn_tasks.id',
      'exp' => 'children.id_parent'
    ]]
  ]
]];

$filters = [
  'conditions' => [[
    'field' => 'bbn_tasks.active',
    'value' => 1
  ], [
    'logic' => 'OR',
    'conditions' => [[
      'field' => 'bbn_tasks.private',
      'value' => 0
    ], [
      'conditions' => [[
        'field' => 'bbn_tasks.private',
        'value' => 1
      ], [
        'field' => 'bbn_tasks.id_user',
        'value' => $id_user
      ]]
    ]]
  ]]
];

if ($isProjectViewer) {
  $filters['conditions'][] = [
    'logic' => 'OR',
    'conditions' => [[
      'conditions' => [[
        'field' => 'bbn_tasks.price',
        'operator' => 'isnull'
      ], [
        'field' => 'parent.price',
        'operator' => 'isnull'
      ]]
    ], [
      'field' => 'my_role.role',
      'operator' => 'isnotnull'
    ]]
  ];
}

if ($isAssigner) {

}

if ($isFinancialManager || $isFinancialViewer) {
  $filters['conditions'][] = [
    'logic' => 'OR',
    'conditions' => [[
      'field' => 'bbn_tasks.price',
      'operator' => 'isnotnull'
    ], [
      'field' => 'parent.price',
      'operator' => 'isnotnull'
    ], [
      'field' => 'children.price',
      'operator' => 'isnotnull'
    ], [
      'field' => 'my_role.role',
      'operator' => 'isnotnull'
    ]]
  ];
}

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
    'logic' => 'OR',
    'conditions' => [[
      'field' => 'bbn_notes_versions.title',
      'operator' => 'contains',
      'value' => $ext_filters['title']
    ], [
      'field' => 'bbn_tasks.ref',
      'operator' => 'contains',
      'value' => $ext_filters['title']
    ]]
  ];
}

if ( !empty($ext_filters['priority']) ){
  $filters['conditions'][] = [
    'field' => 'bbn_tasks.priority',
    'value' => $ext_filters['priority']
  ];
}

if (!empty($ext_filters['role'])
  && ($ext_filters['role'] !== 'all')
) {
  $filters['conditions'][] = [
    'field' => 'my_role.role',
    'value' => $ext_filters['role']
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

//die(\bbn\X::dump($filters));

$grid = new \bbn\Appui\Grid($model->db, $model->data, [
  'table' => 'bbn_tasks',
  'fields' => $fields,
  'join' => $join,
  'filters' => $filters,
  'group_by' => ['bbn_tasks.id']
]);

if ($grid->check()) {
  $data = $grid->getDatatable();
  if (!empty($data['data'])) {
    foreach ($data['data'] as $i => $d) {
      if (!empty($d['reference'])
        && !empty($plugin_model['template'])
        && is_callable($plugin_model['template'])
      ) {
        $data['data'][$i]['reference'] = $plugin_model['template']($model->db, $d);
      }
      $data['data'][$i]['roles'] = $taskCls->infoRoles($d['id']);
      $data['data'][$i]['children'] = $taskCls->getChildren($d['id']);
      $data['data'][$i]['num_children'] = count($data['data'][$i]['children']);
      $data['data'][$i]['children_price'] = $taskCls->getChildrenPrices($d['id']);
      $data['data'][$i]['children_noprice'] = $taskCls->getChildrenNoPrice($d['id']);
      $data['data'][$i]['num_children_noprice'] = count($data['data'][$i]['children_noprice']);
      $data['data'][$i]['parent_has_price'] = $taskCls->parentHasPrice($d['id'], true);
      $data['data'][$i]['parent_unapproved'] = $taskCls->parentIsUnapproved($d['id'], true);
      $data['data'][$i]['parent'] = !empty($d['id_parent']) ? $taskCls->info($d['id_parent'], false, false) : null;
      $data['data'][$i]['approved'] = $taskCls->getApprovalInfo($d['id']);
      $data['data'][$i]['lastChangePrice'] = $taskCls->getPriceLog($d['id']) ?: null;
    }
  }
  return $data;
}