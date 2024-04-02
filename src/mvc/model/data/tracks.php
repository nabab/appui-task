<?php
if (!empty($model->data['data']['startDatetime'])
  && !empty($model->data['data']['endDatetime'])
  && ($t = new \bbn\Appui\Task($model->db))
) {
  $data = array_map(function($track) use($t){
    $title = $t->getTitle($track['id_task']);
    $track['message'] = '';
    if (($n = $t->getTrackNote($track['id']))
      && !empty($n['content'])
    ) {
      $title .= '<div>'.$n['content'].'</div>';
      $track['message'] = $n['content'];
    }
    $track['title'] = $title;
    return $track;
  }, $t->getTracksByDates($model->data['data']['startDatetime'], $model->data['data']['endDatetime']) ?: []);

  return [
    'success' => true,
    'data' => $data,
    'total' => count($data)
  ];
}

return [
  'success' => false
];