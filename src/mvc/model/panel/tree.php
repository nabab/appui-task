<?php
/*
 * Describe what it does or you're a pussy
 *
 **/

/** @var $model \bbn\mvc\model*/
if ( isset($model->data['id']) ){
  $task = new \bbn\appui\tasks($model->db);
  //$statuses = empty($model->data['closed']) ? 'opened|ongoing|holding' : false;
  return $task->get_tree($model->data['id']);
}
return [];
