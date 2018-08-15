<?php
/**
 * Created by PhpStorm.
 * User: BBN
 * Date: 04/06/2017
 * Time: 17:33
 */
if ( !empty($model->data['data']['id_task']) && !isset($model->data['data']['id_alias']) ){
  $cfg = [
    'query' => "
      SELECT bbn_notes.*, first_version.creation, last_version.title, last_version.content,
			  last_version.creation AS last_edit, COUNT(DISTINCT replies.id) AS num_replies,
			  IFNULL(MAX(replies_versions.creation), last_version.creation) AS last_reply,
			  GROUP_CONCAT(DISTINCT LOWER(HEX(versions.id_user)) SEPARATOR ',') AS users
      FROM bbn_notes
        JOIN bbn_notes_versions AS versions
          ON versions.id_note = bbn_notes.id
        JOIN bbn_notes_versions AS last_version
          ON last_version.id_note = bbn_notes.id
        LEFT JOIN bbn_notes_versions AS test_version
          ON test_version.id_note = bbn_notes.id
          AND last_version.version < test_version.version
        JOIN bbn_notes_versions AS first_version
          ON first_version.id_note = bbn_notes.id
          AND first_version.version = 1
        LEFT JOIN bbn_notes AS replies
          ON replies.id_alias = bbn_notes.id
        LEFT JOIN bbn_notes_versions AS replies_versions
          ON replies_versions.id_note = replies.id
        JOIN bbn_tasks_notes
          ON bbn_tasks_notes.id_note = bbn_notes.id
        JOIN bbn_tasks
          ON bbn_tasks.id = bbn_tasks_notes.id_task
          AND bbn_tasks.active = 1",
    'count' => "
      SELECT COUNT(DISTINCT bbn_notes.id)
      FROM bbn_notes
        JOIN bbn_notes_versions AS versions
          ON versions.id_note = bbn_notes.id
        JOIN bbn_notes_versions AS last_version
          ON last_version.id_note = bbn_notes.id
        LEFT JOIN bbn_notes_versions AS test_version
          ON test_version.id_note = bbn_notes.id
          AND last_version.version < test_version.version
        JOIN bbn_notes_versions AS first_version
          ON first_version.id_note = bbn_notes.id
          AND first_version.version = 1
        LEFT JOIN bbn_notes AS replies
          ON replies.id_alias = bbn_notes.id
        LEFT JOIN bbn_notes_versions AS replies_versions
          ON replies_versions.id_note = replies.id
        JOIN bbn_tasks_notes
          ON bbn_tasks_notes.id_note = bbn_notes.id
        JOIN bbn_tasks
          ON bbn_tasks.id = bbn_tasks_notes.id_task
          AND bbn_tasks.active = 1",
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
    'query' => "
      SELECT bbn_notes.*, first_version.creation, last_version.content,
        last_version.creation AS last_edit, COUNT(DISTINCT replies.id) AS num_replies,
        GROUP_CONCAT(DISTINCT LOWER(HEX(versions.id_user)) SEPARATOR ',') AS users,
        parent_version.creation AS parent_creation, parent_version.id_user AS parent_creator,
        parent.active AS parent_active
      FROM bbn_notes
        JOIN bbn_notes_versions AS versions
          ON versions.id_note = bbn_notes.id
        JOIN bbn_notes_versions AS last_version
          ON last_version.id_note = bbn_notes.id
        LEFT JOIN bbn_notes_versions AS test_version
          ON test_version.id_note = bbn_notes.id
          AND last_version.version < test_version.version
        JOIN bbn_notes_versions AS first_version
          ON first_version.id_note = bbn_notes.id
          AND first_version.version = 1
        LEFT JOIN bbn_notes AS replies
          ON replies.id_parent = bbn_notes.id
        LEFT JOIN bbn_notes AS parent
          ON parent.id = bbn_notes.id_parent
        LEFT JOIN bbn_notes_versions AS parent_version
          ON parent_version.id_note = parent.id
          AND parent_version.version = 1",
    'count' => "
      SELECT COUNT(DISTINCT bbn_notes.id)
      FROM bbn_notes
        JOIN bbn_notes_versions AS versions
          ON versions.id_note = bbn_notes.id
        JOIN bbn_notes_versions AS last_version
          ON last_version.id_note = bbn_notes.id
        LEFT JOIN bbn_notes_versions AS test_version
          ON test_version.id_note = bbn_notes.id
          AND last_version.version < test_version.version
        JOIN bbn_notes_versions AS first_version
          ON first_version.id_note = bbn_notes.id
          AND first_version.version = 1
        LEFT JOIN bbn_notes AS replies
          ON replies.id_parent = bbn_notes.id
        LEFT JOIN bbn_notes AS parent
          ON parent.id = bbn_notes.id_parent
        LEFT JOIN bbn_notes_versions AS parent_version
          ON parent_version.id_note = parent.id
          AND parent_version.version = 1",
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
