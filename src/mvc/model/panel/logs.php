<?php
/*
 * Describe what it does or you're a pussy
 *
 **/

/** @var $model \bbn\mvc\model*/
if ( isset($model->data['id_task']) ){
	$pm = new \bbn\appui\task($model->db);
  return $pm->get_log($model->data['id_task']);
}
return [];