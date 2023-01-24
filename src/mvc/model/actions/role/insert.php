<?php
$res = [
  'success' => false
];
if ($model->hasData(['id_task', 'role', 'id_user'], true)) {
	$task = new \bbn\Appui\Task($model->db);
  if (is_array($model->data['id_user'])) {
    $res['success'] = true;
    foreach ($model->data['id_user'] as $idUser) {
      if (!$task->hasRole($model->data['id_task'], $idUser)
        && !$task->addRole($model->data['id_task'], $model->data['role'], $idUser)
      ) {
        $res['success'] = false;
      }
    }
  }
  else {
    $res['success'] = $task->addRole($model->data['id_task'], $model->data['role'], $model->data['id_user']);
  }
  if ($res['success']) {
    $res['roles'] = $task->infoRoles($model->data['id_task']);
  }
}
return $res;
