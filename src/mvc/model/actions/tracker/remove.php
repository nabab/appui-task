<?php
$ok = false;
if ( !empty($model->data['id']) ){
  $notes = new \bbn\Appui\Note($model->db);
  $ok = true;
  if (
    ($note = $model->db->selectOne('bbn_tasks_sessions', 'id_note', ['id' => $model->data['id']])) &&
    !$notes->remove($note)
  ){
    $ok = false;
  }
  if (
    $ok &&
    !$model->db->delete('bbn_tasks_sessions', ['id' => $model->data['id']])
  ){
    $ok = false;
  }
}
return ['success' => $ok];
