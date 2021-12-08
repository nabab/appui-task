<?php
/*
 * Describe what it does
 *
 **/

/** @var $model \bbn\Mvc\Model*/
$pm = new \bbn\Appui\Task($model->db);

return [
  'success' => isset($model->data['title'], $model->data['type']) ? $pm->insert([
    'title' => $model->data['title'],
    'type' => $model->data['type'],
    'deadline' => empty($model->data['deadline']) ? null : $model->data['deadline']
   ]) : false
];
