<?php
$ok = false;
if (
  !empty($model->data['id']) &&
  !empty($model->data['start']) &&
  !empty($model->data['end']) &&
  !empty($model->data['length']) &&
  (strtotime($model->data['end']) > strtotime($model->data['start']))
){
  $ok = true;

  // Message
  if ( !empty($model->data['id_note']) ){
    $notes = new \bbn\appui\notes($model->db);
    if (
      empty($model->data['message']) &&
      !$notes->remove($model->data['id_note'], true)
    ){
      $ok = false;
    }
    if (
      $ok &&
      !empty($model->data['message']) &&
      ($old_message = $notes->get($model->data['id_note'])) &&
      ($old_message['content'] !== $model->data['message']) &&
      !$notes->update($model->data['id_note'], $old_message['title'], $model->data['message'])
    ){
      $ok = false;
    }
  }

  if ( $ok ){
    $old_track = $model->db->select('bbn_tasks_sessions', [], ['id' => $model->data['id']]);
    if (
      (($old_track->start !== $model->data['start']) ||
      ($old_track->end !== $model->data['end'])) &&
      !$model->db->update('bbn_tasks_sessions', [
        'start' => $model->data['start'],
        'length' => $model->data['length']
      ], ['id' => $model->data['id']])
    ){
      $ok = false;
    }
  }
}
return ['success' => $ok];
