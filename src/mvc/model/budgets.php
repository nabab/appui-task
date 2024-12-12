<?php
/**
 * What is my purpose?
 *
 **/

use bbn\X;
use bbn\Str;
/** @var bbn\Mvc\Model $model */

if ($model->hasData('limit')) {
  $task = new bbn\Appui\Task($model->db);
  $unapproved = $task->idState('unapproved');
  $accept_log = $task->idAction('price_approved');
  $cfg = [
    'table' => 'bbn_tasks',
    'fields' => [
      'bbn_tasks.id', 'bbn_tasks.type', 'bbn_tasks.price', 'bbn_tasks.state', 'bbn_tasks.ref',
      'bbn_tasks.id_parent', 'bbn_notes_versions.title',
      'parent_title' => 'parents_notes.title',
      'last_date' => 'SUBSTR(FROM_UNIXTIME(MAX(bbn_tasks_logs.chrono)), 1, 19)',
      'accept_date' => 'SUBSTR(FROM_UNIXTIME(MAX(accepted_logs.chrono)), 1, 19)',
      'accepter' => 'accepted_logs.id_user'
    ],
    'join' => [
      [
        'table' => 'bbn_tasks_logs',
        'on' => [
          [
            'field' => 'bbn_tasks_logs.id_task',
            'exp' => 'bbn_tasks.id'
          ]
        ]
      ], [
        'table' => 'bbn_notes_versions',
        'on' => [
          [
            'field' => 'bbn_tasks.id_note',
            'exp' => 'bbn_notes_versions.id_note'
          ]
        ]
      ], [
        'table' => 'bbn_tasks_logs',
        'alias' => 'accepted_logs',
        'type' => 'left',
        'on' => [
          [
            'field' => 'accepted_logs.id_task',
            'exp' => 'bbn_tasks.id'
          ], [
            'field' => 'accepted_logs.action',
            'value' => $accept_log
          ]
        ]
      ], [
        'table' => 'bbn_tasks',
        'alias' => 'parents',
        'type' => 'left',
        'on' => [
          [
            'field' => 'bbn_tasks.id_parent',
            'exp' => 'parents.id'
          ]
        ],
      ], [
        'table' => 'bbn_notes_versions',
        'alias' => 'parents_notes',
        'type' => 'left',
        'on' => [
          [
            'field' => 'parents.id_note',
            'exp' => 'parents_notes.id_note'
          ], [
            'field' => 'parents_notes.latest',
            'value' => 1
          ]
        ]
      ]
    ],
    'where' => [
      'bbn_notes_versions.latest' => 1,
      'bbn_tasks.private' => 0,
      'bbn_tasks.active' => 1,
      ['bbn_tasks.price', '>', 0]
    ],
    'group_by' => ['bbn_tasks.id']/*,
    'having' => [
      ['last_date', '>', date('Y-m-d H:i:s', strtotime('6 month ago'))]
    ]*/
  ];
  $grid = new bbn\Appui\Grid($model->db, $model->data, $cfg);
  if ($grid->check()) {
    return $grid->getDataTable();
  }

  return ['error' => 'Booh!'];
}
else {
  return [
    'states' => $model->inc->options->textValueOptions($model->inc->options->fromCode('states', 'task', 'appui')),
    'types' => $model->inc->options->textValueOptions($model->inc->options->fromCode('cats', 'task', 'appui'))
  ];
}
