<?php
if ( !empty($model->data['id_task']) ){
  $taskCls = new \bbn\Appui\Task($model->db);
  $res = [
    'success' => $taskCls->approve($model->data['id_task']),
  ];
  if (!empty($res['success'])) {
    $res['approved'] = $taskCls->getApprovedLog($model->data['id_task']);
    function getList($id, $t){
      $list = [$t->info($id)];
      if ($children = $t->getChildren($id)) {
        foreach ($children as $child) {
          $list = array_merge($list, getList($child['id'], $t));
        }
      }
      return $list;
    };
    $res['toUpdate'] = getList($taskCls->getIdRoot($model->data['id_task']) ?: $model->data['id_task'], $taskCls);
  }
  return $res;
}
return ['success' => false];
