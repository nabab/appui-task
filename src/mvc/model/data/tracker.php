<?php
if ( !empty($model->data['data']['id_task']) ){
  $grid = new \bbn\appui\grid($model->db, $model->data, [
    'query' => "
      SELECT id, id_task, id_user, id_note, start,
        IFNULL(`length`, 0) AS `length`, end
      FROM bbn_tasks_sessions
    ",
    'count' => "
      SELECT COUNT(id)
      FROM bbn_tasks_sessions
    ",
    'filters' => [[
      'field' => 'id_task',
      'operator' => 'eq',
      'value' => $model->data['data']['id_task']
    ]],
    'order' => [[
      'field' => 'start',
      'dir' => 'DESC'
    ]]
  ]);
  if ( $grid->check() ){
    $tracks = $grid->get_datatable();
    if ( !empty($tracks['data']) ){
      $notes = new \bbn\appui\notes($model->db);
      foreach ( $tracks['data'] as $i => $t ){
        if (
          !empty($t['id_note']) &&
          ($note = $notes->get($t['id_note']))
        ){
          $tracks['data'][$i]['message'] = $note['content'];
        }
      }
    }
    return $tracks;
  }
}
