<?php
if ( !empty($model->data['data']['id_task']) ){
  $grid = new \bbn\Appui\Grid($model->db, $model->data, [
    'table' => 'bbn_tasks_sessions',
    'fields' => [
      'id',
      'id_task',
      'id_user',
      'id_note',
      'start',
      'length' => "IFNULL(`length`, 0)",
      'end'
    ],
    'filters' => [[
      'field' => 'id_task',
      'operator' => '=',
      'value' => $model->data['data']['id_task']
    ]],
    'order' => [[
      'field' => 'start',
      'dir' => 'DESC'
    ]]
  ]);
  if ( $grid->check() ){
    $tracks = $grid->getDatatable();
    if ( !empty($tracks['data']) ){
      $notes = new \bbn\Appui\Note($model->db);
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
