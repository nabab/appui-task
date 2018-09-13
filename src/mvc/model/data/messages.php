<?php
/**
 * Created by PhpStorm.
 * User: BBN
 * Date: 04/06/2017
 * Time: 17:33
 */
if ( !empty($model->data['data']['id_task']) && !isset($model->data['data']['id_alias']) ){
  $cfg = [
		'table' => 'bbn_notes',
		'fields' => [
			'bbn_notes.id',
			'bbn_notes.id_parent',
			'bbn_notes.id_alias',
			'bbn_notes.id_type',
			'bbn_notes.private',
			'bbn_notes.locked',
			'bbn_notes.pinned',
			'bbn_notes.creator',
			'bbn_notes.active',
			'first_version.creation',
			'last_version.title',
			'last_version.content',
			'last_edit' => 'last_version.creation',
			'num_replies' => 'COUNT(DISTINCT replies.id)',
			'last_reply' => 'IFNULL(MAX(replies_versions.creation), last_version.creation)',
			'users' => 'GROUP_CONCAT(DISTINCT LOWER(HEX(versions.id_user)) SEPARATOR ",")'
		],
		'join' => [[
			'table' => 'bbn_notes_versions',
			'alias' => 'versions',
			'on' => [
				'logic' => 'AND',
				'conditions' => [[
					'field' => 'versions.id_note',
					'operator' => '=',
					'exp' => 'bbn_notes.id'
				]]
			]
		], [
			'table' => 'bbn_notes_versions',
			'alias' => 'last_version',
			'on' => [
				'logic' => 'AND',
				'conditions' => [[
					'field' => 'last_version.id_note',
					'operator' => '=',
					'exp' => 'bbn_notes.id'
				]]
			]
		], [
			'table' => 'bbn_notes_versions',
			'alias' => 'test_version',
			'type' => 'left',
			'on' => [
				'logic' => 'AND',
				'conditions' => [[
					'field' => 'test_version.id_note',
					'operator' => '=',
					'exp' => 'bbn_notes.id'
				], [
					'field' => 'last_version.version',
					'operator' => '<',
					'exp' => 'test_version.version'
				]]
			]
		], [
			'table' => 'bbn_notes_versions',
			'alias' => 'first_version',
			'on' => [
				'logic' => 'AND',
				'conditions' => [[
					'field' => 'first_version.id_note',
					'operator' => '=',
					'exp' => 'bbn_notes.id'
				], [
					'field' => 'first_version.version',
					'operator' => '=',
					'value' => 1
				]]
			]
		], [
			'table' => 'bbn_notes',
			'alias' => 'replies',
			'type' => 'left',
			'on' => [
				'logic' => 'AND',
				'conditions' => [[
					'field' => 'replies.id_alias',
					'operator' => '=',
					'exp' => 'bbn_notes.id'
				]]
			]
		], [
			'table' => 'bbn_notes_versions',
			'alias' => 'replies_versions',
			'type' => 'left',
			'on' => [
				'logic' => 'AND',
				'conditions' => [[
					'field' => 'replies_versions.id_note',
					'operator' => '=',
					'exp' => 'replies.id'
				]]
			]
		], [
			'table' => 'bbn_tasks_notes',
			'on' => [
				'logic' => 'AND',
				'conditions' => [[
					'field' => 'bbn_tasks_notes.id_note',
					'operator' => '=',
					'exp' => 'bbn_notes.id'
				]]
			]
		], [
			'table' => 'bbn_tasks',
			'on' => [
				'logic' => 'AND',
				'conditions' => [[
					'field' => 'bbn_tasks.id',
					'operator' => '=',
					'exp' => 'bbn_tasks_notes.id_task'
				], [
					'field' => 'bbn_tasks.active',
					'operator' => '=',
					'value' => 1
				]]
			]
		]],
    'filters' => [[
      'field' => 'bbn_tasks.id',
      'operator' => 'eq',
      'value' => $model->data['data']['id_task']
    ], [
      'field' => 'bbn_notes.active',
      'operator' => 'eq',
      'value' => 1
    ], [
      'field' => 'bbn_notes.id_parent',
      'operator' => 'isnull'
    ], [
      'field' => 'test_version.version',
      'operator' => 'isnull'
    ], [
      'field' => 'bbn_notes.id_alias',
      'operator' => 'isnull'
    ]],
    'group_by' => 'bbn_notes.id',
    'order' => [[
      'field' => 'last_reply',
      'dir' => 'DESC'
    ], [
      'field' => 'last_edit',
      'dir' => 'DESC'
    ]]
  ];
}
else if ( !empty($model->data['data']['id_alias']) ){
  $cfg = [
    'table' => 'bbn_notes',
    'fields' => [
      'bbn_notes.id',
      'bbn_notes.id_parent',
      'bbn_notes.id_alias',
      'bbn_notes.id_type',
      'bbn_notes.private',
      'bbn_notes.locked',
      'bbn_notes.pinned',
      'bbn_notes.creator',
      'bbn_notes.active',
      'first_version.creation',
      'last_version.content',
      'last_edit' => 'last_version.creation',
      'num_replies' => 'COUNT(DISTINCT replies.id)',
      'users' => 'GROUP_CONCAT(DISTINCT LOWER(HEX(versions.id_user)) SEPARATOR ",")',
      'parent_creation' => 'parent_version.creation',
      'parent_creator' => 'parent_version.id_user',
      'parent_active' => 'parent.active'
    ],
    'join' => [[
      'table' => 'bbn_notes_versions',
      'alias' => 'versions',
      'on' => [
        'logic' => 'AND',
        'conditions' => [[
          'field' => 'versions.id_note',
          'operator' => '=',
          'exp' => 'bbn_notes.id'
        ]]
      ]
    ], [
      'table' => 'bbn_notes_versions',
      'alias' => 'last_version',
      'on' => [
        'logic' => 'AND',
        'conditions' => [[
          'field' => 'last_version.id_note',
          'operator' => '=',
          'exp' => 'bbn_notes.id'
        ]]
      ]
    ], [
      'table' => 'bbn_notes_versions',
      'type' => 'left',
      'alias' => 'test_version',
      'on' => [
        'logic' => 'AND',
        'conditions' => [[
          'field' => 'test_version.id_note',
          'operator' => '=',
          'exp' => 'bbn_notes.id'
        ], [
          'field' => 'last_version.version',
          'operator' => '<',
          'exp' => 'test_version.version'
        ]]
      ]
    ], [
      'table' => 'bbn_notes_versions',
      'alias' => 'first_version',
      'on' => [
        'logic' => 'AND',
        'conditions' => [[
          'field' => 'first_version.id_note',
          'operator' => '=',
          'exp' => 'bbn_notes.id'
        ], [
          'field' => 'first_version.version',
          'operator' => '=',
          'value' => 1
        ]]
      ]
    ], [
      'table' => 'bbn_notes',
      'type' => 'left',
      'alias' => 'replies',
      'on' => [
        'logic' => 'AND',
        'conditions' => [[
          'field' => 'replies.id_parent',
          'operator' => '=',
          'exp' => 'bbn_notes.id'
        ]]
      ]
    ], [
      'table' => 'bbn_notes',
      'type' => 'left',
      'alias' => 'parent',
      'on' => [
        'logic' => 'AND',
        'conditions' => [[
          'field' => 'parent.id',
          'operator' => '=',
          'exp' => 'bbn_notes.id_parent'
        ]]
      ]
    ], [
      'table' => 'bbn_notes_versions',
      'type' => 'left',
      'alias' => 'parent_version',
      'on' => [
        'logic' => 'AND',
        'conditions' => [[
          'field' => 'parent_version.id_note',
          'operator' => '=',
          'exp' => 'parent.id'
        ], [
          'field' => 'parent_version.version',
          'operatort' => '=',
          'value' => 1
        ]]
      ]
    ]],
    'filters' => [[
      'field' => 'bbn_notes.id_alias',
      'operator' => 'eq',
      'value' => $model->data['data']['id_alias']
    ], [
      'field' => 'test_version.version',
      'operator' => 'isnull'
    ], [
      'field' => 'bbn_notes.active',
      'operator' => 'eq',
      'value' => 1
    ]],
    'group_by' => 'bbn_notes.id',
    'order' => [/*[
      'field' => 'last_edit',
      'dir' => 'DESC'
    ], */[
      'field' => 'creation',
      'dir' => 'DESC'
    ]]
  ];
}
$grid = new \bbn\appui\grid($model->db, $model->data, $cfg);
if ( $grid->check() ){
  $d = $grid->get_datatable();
  $notes = new \bbn\appui\notes($model->db);
  if ( is_array($d['data']) && !empty($d['data']) ){
    $ftype = $model->inc->options->from_code('file', 'media', 'notes', 'appui');
    $ltype = $model->inc->options->from_code('link', 'media', 'notes', 'appui');
    foreach ($d['data'] as $i => $n ){
      $d['data'][$i]['files'] = [];
      $d['data'][$i]['links'] = [];
      $medias = $notes->get_medias($n['id']);
      foreach ( $medias as $m ){
        if ( $m['type'] === $ftype ){
          $d['data'][$i]['files'][] = [
            'id' => $m['id'],
            'name' => $m['name'],
            'title' => $m['title'],
            'extension' => '.'.\bbn\str::file_ext($m['name'])
          ];
        }
        if ( $m['type'] === $ltype ){
          $d['data'][$i]['links'][] = $m;
        }
      }
    }
  }
  return $d;
}
