<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 06/10/2017
 * Time: 14:22
 *
 * @var $model \bbn\mvc\model
 */

if ( !empty($model->data['id_task']) ){
  $pm = new \bbn\appui\task($model->db);
  return ['success' => $pm->ping($model->data['id_task'])];
}