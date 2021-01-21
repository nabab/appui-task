<?php
/*
 * Describe what it does or you're a pussy
 *
 **/

/** @var $model \bbn\mvc\model*/

$res = false;
if ( isset($model->data['id_task'], $model->data['id_user']) ){
	$pm = new \bbn\appui\task($model->db);
  $res = $pm->remove_role($model->data['id_task'], $model->data['id_user']);
}
return ['success' => $res];