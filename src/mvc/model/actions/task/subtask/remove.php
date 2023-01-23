<?php
if ($model->hasData('id', true)) {
  $taskCls = new \bbn\Appui\Task($model->db);
  if (($idParent = $model->db->selectOne('bbn_tasks', 'id_parent', ['id' => $model->data['id']]))
    && $model->db->update('bbn_tasks', ['id_parent' => null], ['id' => $model->data['id']])
  ) {
    return [
      'success' => true,
      'children' => $taskCls->getChildren($idParent)
    ];
  }
}
return ['success' => false];
