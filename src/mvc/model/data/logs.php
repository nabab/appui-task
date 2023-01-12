<?php
if (!empty($model->data['data']['id_task'])) {
	$task = new \bbn\Appui\Task($model->db);
	$d = $task->getLog($model->data['data']['id_task']);
  return [
    'data' => $d,
    'total' => count($d)
  ];
}
return [];
