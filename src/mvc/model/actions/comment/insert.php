<?php
/*
 * Describe what it does or you're a pussy
 *
 **/

/** @var $model \bbn\mvc\model*/
if ( !empty($model->data['id']) && !empty($model->data['ref']) ){
  $pm = new \bbn\appui\tasks($model->db);
  $path = BBN_USER_PATH.'tmp/'.$model->data['ref'];
  $model->data['files'] = \bbn\file\dir::get_files($path);
  if ( !empty($model->data['links']) ){
    foreach ( $model->data['links'] as $i => $link ){
      if ( !empty($link['image']) ){
        $model->data['links'][$i]['image'] = $path.'/'.$model->data['links'][$i]['image'];
      }
    }
  }
  $res = $pm->comment($model->data['id'], [
    'files' => $model->data['files'] ?: false,
    'title' => $model->data['title'] ?? '',
    'text' => $model->data['text'] ?? '',
    'links' => $model->data['links'] ?: false
  ]);
  return [
    'success' => $res,
    'comment' => $pm->get_comment($model->data['id'], $res)
  ];
}
