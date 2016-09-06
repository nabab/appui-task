<?php
/*
 * Describe what it does or you're a pussy
 *
 **/

/** @var $model \bbn\mvc\model*/
$pm = new \bbn\appui\task($model->db);
if ( !empty($model->data['search']) ){
  return $pm->get_slist($model->data['search']);
}
else{
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
  if ( isset($data['selection']) ){
    if ( $data['selection'] === 'user' ){
      array_push($res, ['id_user', '=', $model->inc->user->get_id()]);
    }
    else if ( $data['selection'] === 'group' ){
      array_push($res, ['id_group', '=', $model->inc->user->get_group()]);
    }
  }
  return $pm->search($res, $sort);
}
