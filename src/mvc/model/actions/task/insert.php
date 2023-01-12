<?php
/** @var $model \bbn\Mvc\Model*/
$taskCls = new \bbn\Appui\Task($model->db);
$ret = [
  'success' => false
];
if ($model->hasData(['title', 'type'], true)
  && ($newId = $taskCls->insert([
    'title' => $model->data['title'],
    'type' => $model->data['type'],
    'id_parent' => empty($model->data['id_parent']) ? null : $model->data['id_parent'],
    'deadline' => empty($model->data['deadline']) ? null : $model->data['deadline'],
    'private' => empty($model->data['private']) ? 0 : 1
   ]))
) {
  $ret = [
    'success' => true,
    'id' => $newId
  ];
  if (!empty($model->data['id_parent'])) {
    $ret['children'] = $taskCls->getChildren($model->data['id_parent']);
  }
}
return $ret;
