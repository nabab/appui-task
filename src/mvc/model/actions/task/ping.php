<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 06/10/2017
 * Time: 14:22
 *
 * @var $model \bbn\Mvc\Model
 */

if ($model->hasData('id_task', true)){
  $pm = new \bbn\Appui\Task($model->db);
  return ['success' => $pm->ping($model->data['id_task'])];
}
return ['success' => false];