<?php
/*
 * Describe what it does or you're a pussy
 *
 **/

/** @var $model \bbn\mvc\model*/
$pm = new \bbn\appui\task($model->db);
$grid = new \bbn\appui\grid($model->db, $model->data, 'bbn_tasks', [
  'reference',
  'role',
  'last_action'
]);
$where1 = $grid->filter($model->data['filter'], true);
$ops = [
  'eq' => '=',
  'neq' => '!=',
  'gt' => '>',
  'gte' => '>=',
  'lt' => '<',
  'lte' => '<=',
  'contains' => 'LIKE',
  'isnotnull' => 'IS NOT NULL',
  'isnull' => 'IS NULL',
];
$res = [];
$done = [];
if ( isset($model->data['filter'], $model->data['filter']['filters']) ){
  $fs =& $model->data['filter']['filters'];
  foreach ( $fs as $f ){
    if ( isset($f['field'], $f['operator'], $ops[$f['operator']]) ){
      if ( !isset($done[$f['field']]) ){
        $done[$f['field']] = count($res);
        array_push($res, [$f['field'], $ops[$f['operator']], $f['value']]);
      }
      else{
        if ( !is_array($res[$done[$f['field']]][2]) ){
          $res[$done[$f['field']]][2] = [$res[$done[$f['field']]][2]];
        }
        array_push($res[$done[$f['field']]][2], $f['value']);
      }
    }
    else if ( isset($f['filters']) && count($f['filters']) ){
      $r = [$f['filters'][0]['field'], '=', []];
      foreach ( $f['filters'] as $f2 ){
        array_push($r[2], $f2['value']);
      }
      array_push($res, $r);
    }
  }
}
$sort = [];
if ( !empty($model->data['sort']) ){
  foreach ( $model->data['sort'] as $s ){
    if ( isset($s['dir'], $s['field']) ){
      $sort[$s['field']] = $s['dir'];
    }
  }
}
if ( isset($model->data['selection']) ){
  if ( $model->data['selection'] === 'user' ){
    array_push($res, ['my_user', '=', $model->inc->user->get_id()]);
  }
  else if ( $model->data['selection'] === 'group' ){
    array_push($res, ['my_group', '=', $model->inc->user->get_group()]);
  }
}
return $pm->search($res, $sort, $model->data['skip'] ?? 0, $model->data['take'] ?? 25);
