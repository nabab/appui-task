<?php
/*
 * Describe what it does or you're a pussy
 *
 **/

/** @var $model \bbn\mvc\model*/
if ( !empty($model->data['data']['id_task']) ){
	$task = new \bbn\appui\tasks($model->db);
	$d = $task->get_log($model->data['data']['id_task']);
  return [
    'data' => $d,
    'total' => count($d)
  ];
}
return [];