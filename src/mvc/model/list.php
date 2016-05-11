<?php
if ( isset($this->data['title']) && !isset($this->data['action']) ){
  $this->data['action'] = empty($this->data['id']) ? 'insert' : 'update';
}
if ( !isset($this->data['action']) ){
  $this->data['action'] = false;
}
$time = time();
$date = date('Y-m-d H:i:s', $time);
$this->data['creation_date'] = \bbn\date::format($time, 'js');
$tache = new \bbn\appui\task($this->db, $this->inc->user, $this->inc->options);
switch ( $this->data['action'] ){

  case 'new_comment':
  if ( !empty($this->data['comment']) ){
    $this->data['id'] = $tache->comment($this->data['id_task'], $this->data['comment']);
    return $this->data;
  }
  break;

  case 'delete':
  if ( isset($this->data['id']) ){
    $this->data['id'] = $tache->delete($this->data['id']);
    return $this->data;
  }
  break;

  case 'insert':
  if ( isset($this->data['title'], $this->data['comment']) ){
    $this->data['id'] = $tache->insert($this->data['title'], $this->data['comment'], empty($this->data['deadline']) ? null : $this->data['deadline']);
    return $this->data;
  }
  break;

  case 'update':
  if ( isset($this->data['id'], $this->data['title'], $this->data['status'], $this->data['priority']) ){
    $this->data['id'] = $tache->update($this->data['id'], $this->data['title'], $this->data['status'], $this->data['priority'], empty($this->data['deadline']) ? null : $this->data['deadline']);
    return $this->data;
  }
  break;

  case 'up':
  if ( isset($this->data['id']) ){
    $this->data['id'] = $tache->up($this->data['id']);
    return $this->data;
  }
  break;

  case 'down':
  if ( isset($this->data['id']) ){
    $this->data['id'] = $tache->down($this->data['id']);
    return $this->data;
  }
  break;

  case "subscribe":
  if ( isset($this->data['id']) ){
    $is_subscribed = $this->db->count('bbn_tasks_cc', ['id_user' => $this->inc->user->get_id(), 'id_task' => $this->data['id']]);
    if ( $is_subscribed && $tache->unsubscribe($this->data['id']) ){
      return [
        'subscribed' => 0,
        'success' => 1
      ];
    }
    else if ( !$is_subscribed && $tache->subscribe($this->data['id']) ){
      return [
        'subscribed' => 1,
        'success' => 1
      ];
    }
  }
  return ['success' => 0];
  break;

  default:
  $grid = new \bbn\appui\grid($this->db, $this->data, isset($this->data['id_task']) ? 'bbn_tasks_comments' : 'bbn_tasks', isset($this->data['id_task']) ? [] : ['id_user', 'title', 'duration', 'last_activity', 'status', 'comments', 'priority', 'deadline']);
  if ( $grid->check() ){
    $where = $grid->where();
    if ( !empty($where) ){
      $where = " AND $where ";
    }
    $sort = $grid->order();
    if ( !empty($sort) ){
      $sort = " ORDER BY $sort ";
    }
    if ( isset($this->data['id_task']) ){
      $count = $this->db->get_one("
        SELECT COUNT(id)
        FROM bbn_tasks_comments
        WHERE id_task = ?
        $where",
        $this->data['id_task']);

      $data = array_map(function($a){
      	$a['comment'] = nl2br($a['comment'], false);
        return $a;
      }, $this->db->get_rows("
        SELECT *
        FROM bbn_tasks_comments
        WHERE id_task = ?
        $where
        $sort
        LIMIT {$grid->start()}, {$grid->limit()}
      ", $this->data['id_task']));

      return [
        'data' => $data,
        'total' => $count
      ];
    }
    else{
      $sql = "
        SELECT bbn_tasks.*, COUNT(DISTINCT bbn_tasks_comments.id) AS `comments`, `status`, crea,
        GREATEST(crea, MAX(bbn_tasks_comments.creation_date)) AS `last_activity`,
        TIMESTAMPDIFF(
        	SECOND,
          bbn_tasks.creation_date,
          IF ( status != 'résolu', NOW(), GREATEST(crea, MAX(bbn_tasks_comments.creation_date)) )
        ) AS `duration`
        FROM `bbn_tasks`
          JOIN (
                SELECT id_task, `status`, creation_date AS crea
                FROM bbn_tasks_status
                ORDER BY creation_date DESC
            ) AS statuses
              ON statuses.id_task = bbn_tasks.id
          JOIN bbn_tasks_comments
            ON bbn_tasks_comments.id_task = bbn_tasks.id
        GROUP BY bbn_tasks.id";
      $count = $this->db->get_one("
      	SELECT COUNT(*)
        FROM ($sql) AS q
        WHERE 1
        $where");
      $data = $this->db->get_rows("
        SELECT * FROM (
          SELECT q.*, COUNT(bbn_tasks_cc.id_task) AS subscribed
          FROM ($sql) AS q
            LEFT JOIN bbn_tasks_cc
              ON bbn_tasks_cc.id_task = q.id
              AND bbn_tasks_cc.id_user = ?
          GROUP BY q.id
        ) AS r
        WHERE 1
        $where
        $sort
        LIMIT {$grid->start()}, {$grid->limit()}",
        $this->inc->user->get_id());
      if ( !empty($data) ){
        $data[0]['where'] = $where;
        $data[0]['sort'] = $sort;
      }
    }
    return [
      'data' => $data,
      'total' => $count
    ];
  }
}