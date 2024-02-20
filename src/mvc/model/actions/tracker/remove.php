<?php
use bbn\X;
use bbn\Appui\Note;
$res = [];
if (!empty($model->data['id'])) {
  $notes = new Note($model->db);
  $ok = $model->inc->user->isAdmin();
  if (!$ok) {
    if (!($ok = $model->inc->user->getId() === $model->db->selectOne('bbn_tasks_sessions', 'id_user', ['id' => $model->data['id']]))) {
      $res['error'] = X::_("You don't have the right to remove this session");
    }
  }
  if ($ok
      && ($note = $model->db->selectOne('bbn_tasks_sessions', 'id_note', ['id' => $model->data['id']]))
      && !$notes->remove($note)
  ){
    $ok = false;
  }
  if (
    $ok &&
    !$model->db->delete('bbn_tasks_sessions', ['id' => $model->data['id']])
  ){
    $ok = false;
  }
  $res['success'] = $ok;
}

return $res;
