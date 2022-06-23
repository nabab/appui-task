<?php
/*
 * Describe what it does or you're a pussy
 *
 **/

/** @var $model \bbn\Mvc\Model*/

$res = false;
if ($model->hasData(['id_task', 'role', 'id_user'], true)) {
	$task = new \bbn\Appui\Task($model->db);
  if (is_array($model->data['id_user'])) {
    $res = true;
    foreach ($model->data['id_user'] as $idUser) {
      if (!$task->hasRole($model->data['id_task'], $idUser)
        && !$task->addRole($model->data['id_task'], $model->data['role'], $idUser)
      ) {
        $res = false;
      }
    }
  }
  else {
    $res = $task->addRole($model->data['id_task'], $model->data['role'], $model->data['id_user']);
  }
}
return ['success' => $res];