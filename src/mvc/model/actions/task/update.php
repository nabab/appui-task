<?php
/*
 * Describe what it does or you're a pussy
 *
 **/

/** @var $model \bbn\mvc\model*/
$pm = new \bbn\appui\task($model->db);
return [
  'success' => isset($model->data['id_task'], $model->data['prop'], $model->data['val']) ? $pm->update($model->data['id_task'], $model->data['prop'], $model->data['val']) : false
];