<?php
/*
 * Describe what it does or you're a pussy
 *
 **/

/** @var $model \bbn\Mvc\Model*/
if ( !empty($model->data['data']['id_task']) ){
	$task = new \bbn\Appui\Task($model->db);
	$d = $task->getLog($model->data['data']['id_task']);
  return [
    'data' => $d,
    'total' => \count($d)
  ];
}
return [];