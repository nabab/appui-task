<?php
if ($model->hasData(['id_task', 'prop'], true)) {
  $taskCls = new \bbn\Appui\Task($model->db);
  $idTask = $model->data['id_task'];
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
      'tracker' => $taskCls->getTrack($idTask),
      'trackers' => $taskCls->getTracks($idTask)
    ]
  );
  function getList($id, $t){
    $list = [$t->info($id)];
    if ($children = $t->getChildren($id)) {
      foreach ($children as $child) {
        $list = array_merge($list, getList($child['id'], $t));
      }
    }
    return $list;
  };
  $res['toUpdate'] = getList($taskCls->getIdRoot($idTask) ?: $idTask, $taskCls);
  return $res;
}
return ['success' => false];
