<?php
/*
 * Describe what it does or you're a pussy
 *
 **/

/** @var $model \bbn\Mvc\Model*/

$res = false;
if ($model->hasData(['id_task' ,'id_user'], true)) {
	$task = new \bbn\Appui\Task($model->db);
  $res = $task->removeRole($model->data['id_task'], $model->data['id_user']);
}
return ['success' => $res];
