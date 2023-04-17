<?php
use \bbn\X;
$tasks = $ctrl->db->selectAll('bbn_tasks', [], [], ['creation_date' => 'asc']);
$year = (int)date('Y', strtotime($tasks[0]->creation_date));
$eid = 0;
$did = 0;
foreach ($tasks as $t) {
  $y = (int)date('Y', strtotime($t->creation_date));
  if ($y !== $year) {
    $year = $y;
    $eid = 0;
  }
  $eid++;
	$did += $ctrl->db->update('bbn_tasks', ['easy_id' => $eid], ['id' => $t->id]);
}
X::adump($did);
