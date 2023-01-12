<?php
use \bbn\X;
use \bbn\Appui\Note;

$did = 0;
if (($tasks = $ctrl->db->selectAll([
    'table' => 'bbn_tasks',
    'fields' => [],
    'where' => [
      'conditions' => [[
        'field' => 'id_note',
        'operator' => 'isnull'
      ], [
        'field' => 'title',
        'operator' => '!=',
        'value' => ''
      ], [
        'field' => 'active',
        'value' => 1
      ]]
    ]
  ]))
  && ($idType = $ctrl->inc->options->fromCode('tasks', 'types', 'note', 'appui'))
) {
  $tot = count($tasks);
  echo "$tot tasks to process" . PHP_EOL;
  $notes = new Note($ctrl->db);
  foreach ($tasks as $i => $task) {
    echo ($i + 1) . "/$tot - ";
    if (($idNote = $notes->insert($task->title, '', $idType))
      && $ctrl->db->update('bbn_tasks', [
        'id_note' => $idNote,
        'title' => ''
      ], ['id' => $task->id])
    ) {
      $did++;
      echo 'SUCCESS' . PHP_EOL;
    }
    else {
      echo 'ERROR!' . PHP_EOL;
      X::log($task, 'tasks_notes');
    }
  }
}
echo 'END' . PHP_EOL . "SUCCESS $did/$tot" . PHP_EOL;
if ($err = $tot - $did) {
  echo "ERROR $err/$tot" . PHP_EOL;
}
