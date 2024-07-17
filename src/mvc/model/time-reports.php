<?php
/**
 * What is my purpose?
 *
 **/

use bbn\X;
use bbn\Str;
use bbn\Appui\Task;
/** @var $model \bbn\Mvc\Model*/

if ($model->hasData('limit')) {
  $task = new Task($model->db);
  $unapproved = $task->idState('unapproved');
  $accept_log = $task->idAction('price_approved');
  /*
SELECT id_task, bbn_notes_versions.title, MIN(start) AS beginning, MAX(start) AS end, CEIL(SUM(bbn_tasks_sessions.length)/3600) AS hours
FROM `bbn_tasks_sessions`
JOIN bbn_tasks ON bbn_tasks_sessions.id_task = bbn_tasks.id
JOIN bbn_notes_versions ON bbn_notes_versions.id_note = bbn_tasks.id_note
AND bbn_notes_versions.latest = 1
WHERE start > '2023-12-31 23:59:59'
GROUP BY id_task;
*/
  $cfg = [
    'table' => 'bbn_tasks_sessions',
    'fields' => [
      'bbn_tasks.id', 'bbn_notes_versions.title',
      'bbn_tasks_sessions.id_user',
      'bbn_notes_versions.title',
      'beginning' => 'MIN(start)',
      'end' => 'MAX(start)',
      'hours' => 'CEIL(SUM(bbn_tasks_sessions.length)/3600) '
    ],
    'join' => [
      [
        'table' => 'bbn_tasks',
        'on' => [
          [
            'field' => 'bbn_tasks_sessions.id_task',
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
      ]
    ],
    'where' => [
      'latest' => 1,
      'bbn_tasks.private' => 0,
      'bbn_tasks.active' => 1
    ],
    'group_by' => ['bbn_tasks.id', 'bbn_tasks_sessions.id_user']/*,
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
