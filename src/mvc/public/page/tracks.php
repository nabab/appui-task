<?php
use \bbn\Str;

if ($ctrl->hasArguments()
  && !empty($ctrl->arguments[0])
  && !!Str::isUid($ctrl->arguments[0])
) {
  $ctrl->addData([
    'idTrak' => $ctrl->arguments[0]
  ]);

}

$taskCls = new \bbn\Appui\Task($ctrl->db);
$ctrl->addData([
  'states' => $taskCls->states()
]);

$ctrl->combo(_('Tracks editor'), true)->setIcon('nf nf-md-chart_timeline');
