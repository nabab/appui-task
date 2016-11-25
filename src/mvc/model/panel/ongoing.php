<?php
/*
 * Describe what it does or you're a pussy
 *
 **/

/** @var $model \bbn\mvc\model*/
$pm = new \bbn\appui\tasks($model->db);
return $pm->get_mine(empty($model->data['id']) ? null : $model->data['id']);
