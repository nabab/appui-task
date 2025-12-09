<?php


/** @var bbn\Mvc\Model $model */

$res = ['success' => false];
if ($model->hasData(['id_task' ,'id_user'], true)) {
	$task = new \bbn\Appui\Task($model->db);
  if ($task->removeRole($model->data['id_task'], $model->data['id_user'])) {
    $res = [
      'success' => true,
      'roles' => $task->infoRoles($model->data['id_task'])
    ];
  }
}
return $res;
