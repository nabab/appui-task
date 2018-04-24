<?php
/*
 * Describe what it does or you're a pussy
 *
 **/

/** @var $model \bbn\mvc\model*/

if ( isset($model->data['search']) ){
  $pm = new \bbn\appui\tasks($model->db);
  if ( $rows = $pm->search($model->data['search']) ){
    return ['rows' => $rows];
  }
}
return ['rows' => []];