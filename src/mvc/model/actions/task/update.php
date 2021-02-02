<?php
/*
 * Describe what it does or you're a pussy
 *
 **/

/** @var $model \bbn\Mvc\Model*/
$tasks = new \bbn\Appui\Task($model->db);
$res = [
  'success' => !empty($model->data['id_task']) && !empty($model->data['prop']) ?
    $tasks->update(
      $model->data['id_task'],
      $model->data['prop'],
      isset($model->data['val']) ? $model->data['val'] : null) :
    false
];
if ( ($model->data['prop'] === 'price') && !empty($model->data['id_task']) ){
  $res['lastChangePrice'] = $tasks->getPriceLog($model->data['id_task']);
}
if ( $model->data['prop'] === 'state' ){
  $res['tracker'] = $tasks->getTrack($model->data['id_task']);
  $res['trackers'] = $tasks->getTracks($model->data['id_task']);
}
return $res;
