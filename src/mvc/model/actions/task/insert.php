<?php
/** @var $model \bbn\Mvc\Model*/
$pm = new \bbn\Appui\Task($model->db);
$newId = isset($model->data['title'], $model->data['type']) ? $pm->insert([
  'title' => $model->data['title'],
  'type' => $model->data['type'],
  'id_parent' => !empty($model->data['id_parent']) ? $model->data['id_parent'] : null,
  'deadline' => empty($model->data['deadline']) ? null : $model->data['deadline']
 ]) : false;
$ret = [
  'success' => $newId,
  'id' => $newId
];
if (!empty($newId)) {
  $ret['id'] = $newId;
  if (!empty($model->data['id_parent'])) {
    $ret['children'] = $pm->getChildren($model->data['id_parent']);
  }
}
return $ret;