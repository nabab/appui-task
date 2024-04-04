<?php
use bbn\X;
use bbn\Appui\Task;

$res = [
  'success' => false
];
if (!empty($model->data['id'])) {
  $taskCls = new Task($model->db);
  if ($track = $taskCls->getTrack($model->data['id'])) {
    if ($model->inc->user->getId() !== $track['id_user']) {
      $res['error'] = X::_("You don't have the right to remove this track");
    }
    else {
      $res['success'] = $taskCls->deleteTrack($model->data['id']);
    }
  }
}

return $res;
