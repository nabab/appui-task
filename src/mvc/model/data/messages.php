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
      SELECT bbn_notes.*, bbn_notes.id AS idnote,
        (
          SELECT content
          FROM bbn_notes_versions
          WHERE id_note = idnote
          ORDER BY version DESC
          LIMIT 1
        ) AS content,
        (
          SELECT title
          FROM bbn_notes_versions
          WHERE id_note = idnote
          ORDER BY version DESC
          LIMIT 1
        ) AS title,
        (
          SELECT creation
          FROM bbn_notes_versions
          WHERE id_note = idnote
          ORDER BY version ASC
          LIMIT 1
        ) AS creation,
        (
          SELECT creation
          FROM bbn_notes_versions
          WHERE id_note = idnote
          ORDER BY version DESC
          LIMIT 1
        ) AS last_edit,
        (
          SELECT COUNT(id)
          FROM bbn_notes
          WHERE id_alias = idnote
            AND active = 1
        ) AS num_replies,
        IFNULL((
          SELECT creation
          FROM bbn_notes_versions
            JOIN bbn_notes
              ON bbn_notes_versions.id_note = bbn_notes.id
          WHERE bbn_notes.id_alias = idnote
          ORDER BY bbn_notes_versions.creation DESC
          LIMIT 1
        ), (
          SELECT creation
          FROM bbn_notes_versions
          WHERE id_note = idnote
          ORDER BY version DESC
          LIMIT 1
        )) AS last_reply,
        (
          SELECT GROUP_CONCAT(DISTINCT HEX(bbn_notes_versions.id_user) SEPARATOR ',')
          FROM bbn_notes_versions
            JOIN bbn_notes
              ON bbn_notes_versions.id_note = bbn_notes.id
          WHERE bbn_notes_versions.id_note = idnote
            AND bbn_notes.creator != bbn_notes_versions.id_user 
          GROUP BY bbn_notes_versions.id_note
          LIMIT 1
        ) AS users
      FROM bbn_notes_versions
        JOIN bbn_notes
          ON bbn_notes_versions.id_note = bbn_notes.id
          AND bbn_notes.active = 1
        JOIN bbn_tasks_notes
          ON bbn_tasks_notes.id_note = bbn_notes_versions.id_note
          AND bbn_tasks_notes.active = 1
        JOIN bbn_tasks
          ON bbn_tasks.id = bbn_tasks_notes.id_task
          AND bbn_tasks.active = 1",
    'count' => "
      SELECT COUNT(bbn_tasks_notes.id_note)
      FROM bbn_tasks_notes
        JOIN bbn_notes
          ON bbn_tasks_notes.id_note = bbn_notes.id
          AND bbn_notes.active = 1
        JOIN bbn_tasks
          ON bbn_tasks.id = bbn_tasks_notes.id_task
          AND bbn_tasks.active = 1",
    'filters' => [[
      'field' => 'bbn_tasks.id',
      'operator' => 'eq',
      'value' => $model->data['data']['id_task']
    ], [
      'field' => 'bbn_notes.id_parent',
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
      SELECT bbn_notes.*, bbn_notes.id AS idnote, bbn_notes.id_parent AS idparent, n.creator AS parent_creator, 
        n.active AS parent_active,
        (
          SELECT content
          FROM bbn_notes_versions
          WHERE id_note = idnote
          ORDER BY version DESC
          LIMIT 1
        ) AS content,
        (
          SELECT creation
          FROM bbn_notes_versions
          WHERE id_note = idnote
          ORDER BY version ASC
          LIMIT 1
        ) AS creation,
        (
          SELECT creation
          FROM bbn_notes_versions
          WHERE id_note = idnote
          ORDER BY version DESC
          LIMIT 1
        ) AS last_edit,
        (
          SELECT COUNT(id)
          FROM bbn_notes
          WHERE id_parent = idnote
            AND active = 1
        ) AS num_replies,
        (
          SELECT creation
          FROM bbn_notes_versions
          WHERE id_note = idparent
          ORDER BY version ASC
          LIMIT 1
        ) AS parent_creation,
        (
          SELECT GROUP_CONCAT(DISTINCT HEX(bbn_notes_versions.id_user) SEPARATOR ',')
          FROM bbn_notes_versions
            JOIN bbn_notes
              ON bbn_notes_versions.id_note = bbn_notes.id
          WHERE bbn_notes_versions.id_note = idnote
            AND bbn_notes.creator != bbn_notes_versions.id_user 
          GROUP BY bbn_notes_versions.id_note
          LIMIT 1
        ) AS users
      FROM bbn_notes
        JOIN bbn_notes AS n
          ON bbn_notes.id_parent = n.id",
    'count' => "
      SELECT COUNT(bbn_notes.id)
      FROM bbn_notes",
    'filters' => [[
      'field' => 'bbn_notes.id_alias',
      'operator' => 'eq',
      'value' => $model->data['data']['id_alias']
    ], [
      'field' => 'bbn_notes.active',
      'operator' => 'eq',
      'value' => 1
    ]],
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