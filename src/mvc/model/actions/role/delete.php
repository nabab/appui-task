<?php
/*
 * Describe what it does or you're a pussy
 *
 **/

/** @var $model \bbn\Mvc\Model*/

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
