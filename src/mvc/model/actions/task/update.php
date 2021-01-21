<?php
/*
 * Describe what it does or you're a pussy
 *
 **/

/** @var $model \bbn\mvc\model*/
$tasks = new \bbn\appui\task($model->db);
$res = [
  'success' => !empty($model->data['id_task']) && !empty($model->data['prop']) ?
    $tasks->update(
      $model->data['id_task'],
      $model->data['prop'],
      isset($model->data['val']) ? $model->data['val'] : null) :
    false
];
if ( ($model->data['prop'] === 'price') && !empty($model->data['id_task']) ){
  $res['lastChangePrice'] = $tasks->get_price_log($model->data['id_task']);
}
if ( $model->data['prop'] === 'state' ){
  $res['tracker'] = $tasks->get_track($model->data['id_task']);
  $res['trackers'] = $tasks->get_tracks($model->data['id_task']);
}
return $res;
