<?php
if (
  !empty($model->data['ref']) &&
	!empty($model->data['text']) &&
	!empty($model->data['id_parent']) &&
	!empty($model->data['id_alias']) &&
	($id_type = $model->inc->options->from_code('tasks', 'types', 'notes', 'appui'))
){
	$notes = new \bbn\appui\notes($model->db);
  if ( $id_note = $notes->insert(
		$model->data['title'] ?: '',
		$model->data['text'],
		$id_type,
		false,
    $model->data['locked'],
		$model->data['id_parent'],
		$model->data['id_alias']
	) ){
    $ok = true;
    $path = BBN_USER_PATH.'tmp/'.$model->data['ref'].'/';
    if ( is_array($model->data['files']) && !empty($model->data['files']) ){
      foreach ($model->data['files'] as $f ){
        if ( is_file($path.$f['name']) &&
          !$notes->add_media($id_note, $path.$f['name'])
        ){
          $ok = false;
        }
      }
    }
    if ( is_array($model->data['links']) && !empty($model->data['links']) ){
      foreach ($model->data['links'] as $l ){
        if ( is_file($path.$l['image']) &&
          !$notes->add_media(
            $id_note,
            $path.$l['image'],
            json_encode([
              'url' => $l['content']['url'],
              'description' => $l['content']['description']
            ]),
            $l['title'],
            'link'
          )
        ){
          $ok = false;
        }
      }
    }
    if ( $ok ){
      /** @todo To remove this and add an apposite function in grid */
      $model->get_model($model->plugin_url('appui-ide').'/data_cache', [
        'deleteCache' => 'bbn_cache/bbn/appui/grid',
        'deleteContent' => 0
      ]);
      return [
        'success' => true
      ];
    }
  }
}
