<?php
$tasks = new \bbn\Appui\Task($model->db);
return [
  'success' => true,
  'data' => [
    'active' => $tasks->getActiveTrack(),
    'list' => $tasks->getTasksTracks()
  ]
];
