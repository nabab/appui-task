<?php
if ($model->hasData(['id_task', 'role'], true)
  && ($model->hasData('toAdd', true) || $model->hasData('toRemove', true))
) {
  $taskCls = new \bbn\Appui\Task($model->db);
  $removed = [];
  $added = [];
  foreach ($model->data['toRemove'] as $idUser) {
    if (!!$taskCls->removeRole($model->data['id_task'], $idUser)) {
      $removed[] = $idUser;
    }
  }
  foreach ($model->data['toAdd'] as $idUser) {
    if (!!$taskCls->addRole($model->data['id_task'], $model->data['role'], $idUser)) {
      $added[] = $idUser;
    }
  }
  return [
    'success' => !empty($added) || !empty($removed),
    'removed' => $removed,
    'added' => $added
  ];
}
return [
  'success' => false
];
