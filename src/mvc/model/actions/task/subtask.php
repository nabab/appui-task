<?php
if ($model->hasData(['id', 'idParent'], true)) {
  $taskCls = new \bbn\Appui\Task($model->db);
  if ($model->db->update('bbn_tasks', ['id_parent' => $model->data['idParent']], ['id' => $model->data['id']])) {
    return [
      'success' => true,
      'children' => $taskCls->getChildren($model->data['idParent'])
    ];
  }
}
return ['success' => false];
