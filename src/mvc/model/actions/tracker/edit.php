<?php
$ok = false;
if (!empty($model->data['id'])){
  if (!empty($model->data['start'])
    && !empty($model->data['end'])
    && !empty($model->data['length'])
    && (strtotime($model->data['end']) > strtotime($model->data['start']))
    && ($oldTrack = $model->db->select('bbn_tasks_sessions', [], ['id' => $model->data['id']]))
  ) {
    $ok = true;

    // Message
    if (!empty($model->data['id_note'])) {
      $notes = new \bbn\Appui\Note($model->db);
      if (empty($model->data['message'])) {
        if ($notes->remove($model->data['id_note'], true)) {
          $model->data['id_note'] = null;
        }
        else {
          $ok = false;
        }
      }
      if ($ok
        && !empty($model->data['message'])
        && ($oldMessage = $notes->get($model->data['id_note']))
        && ($oldMessage['content'] !== $model->data['message'])
        && !$notes->update($model->data['id_note'], $oldMessage['title'], $model->data['message'])
      ) {
        $ok = false;
      }
    }
    else if (!empty($model->data['message'])) {
      $taskCls = new \bbn\Appui\Task($model->db);
      $start = date('d M Y H:i', strtotime($model->data['start']));
      $end = date('d M Y H:i', strtotime($model->data['end']));
      $model->data['id_note'] = $taskCls->comment($oldTrack->id_task, [
        'title' => \bbn\X::_('Report tracker').' '.$start.' - '.$end,
        'text' => $model->data['message']
      ]);
    }

    if ($ok
      && (($oldTrack->start !== $model->data['start'])
        || ($oldTrack->length !== $model->data['length'])
        || ($oldTrack->id_note !== $model->data['id_note']))
      && !$model->db->update('bbn_tasks_sessions', [
        'id_note' => !empty($model->data['id_note']) ? $model->data['id_note'] : null,
        'start' => $model->data['start'],
        'length' => $model->data['length']
      ], [
        'id' => $model->data['id']
      ])
    ) {
      $ok = false;
    }
  }
  else if (!empty($model->data['toTask'])) {
    $taskCls = new \bbn\Appui\Task($model->db);
    $ok = $taskCls->moveTrack($model->data['id'], $model->data['toTask']);
  }
}

return ['success' => $ok];
