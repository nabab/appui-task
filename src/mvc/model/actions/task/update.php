<?php
if ($model->hasData(['id_task', 'prop'], true)) {
  $taskCls = new \bbn\Appui\Task($model->db);
  $idTask = $model->data['id_task'];
  $includeDeleted = false;
  function getList($id, $t, $d){
    $list = [$t->info($id)];
    if ($children = $t->getChildren($id, $d)) {
      foreach ($children as $child) {
        $list = array_merge($list, getList($child['id'], $t, $d));
      }
    }
    return $list;
  };
  if ($model->data['prop'] === 'state') {
    $cancelState = $taskCls->idState('canceled');
    $deletedState = $taskCls->idState('deleted');
    if (($model->data['val'] === $cancelState)
      || ($model->data['val'] === $deletedState)
    ) {
      $includeDeleted = true;
    }
  }
  $res = [
    'success' => $taskCls->update(
        $idTask,
        $model->data['prop'],
        isset($model->data['val']) ? $model->data['val'] : null
    )
  ];
  $res['data'] = \bbn\X::mergeArrays(
    $taskCls->info($idTask),
    [
      'tracker' => $taskCls->getActiveTrack(false, $idTask),
      'trackers' => $taskCls->getTracks($idTask)
    ]
  );
  $res['toUpdate'] = getList($taskCls->getIdRoot($idTask) ?: $idTask, $taskCls, $includeDeleted);
  return $res;
}
return ['success' => false];
