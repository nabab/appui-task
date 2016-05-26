<?php
/*
 * Describe what it does or you're a pussy
 *
 **/

/** @var $this \bbn\mvc\model*/
$pm = new \bbn\appui\task($this->db);
if ( !empty($this->data['search']) ){
  return $pm->get_slist($this->data['search']);
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
  if ( isset($this->data['filter'], $this->data['filter']['filters']) ){
    $fs =& $this->data['filter']['filters'];
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
  if ( !empty($this->data['sort']) ){
    foreach ( $this->data['sort'] as $s ){
      if ( isset($s['dir'], $s['field']) ){
        $sort[$s['field']] = $s['dir'];
      }
    }
  }
  if ( isset($data['selection']) ){
    if ( $data['selection'] === 'user' ){
      array_push($res, ['id_user', '=', $this->inc->user->get_id()]);
    }
    else if ( $data['selection'] === 'group' ){
      array_push($res, ['id_group', '=', $this->inc->user->get_group()]);
    }
  }
  return $pm->search($res, $sort);
}
