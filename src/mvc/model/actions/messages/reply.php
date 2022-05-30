<?php

use bbn\Appui\Note;

if ($model->hasData(['ref', 'text', 'id_parent', 'id_alias'], true) &&
	($id_type = $model->inc->options->fromCode('tasks', 'types', 'note', 'appui'))
){
	$notes = new Note($model->db);
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
    $path = BBN_USER_PATH . 'tmp/'.$model->data['ref'] . '/';
    if ( is_array($model->data['files']) && !empty($model->data['files']) ){
      foreach ($model->data['files'] as $f ){
        if ( is_file($path.$f['name']) &&
          !$notes->addMedia($id_note, $path.$f['name'])
        ){
          $ok = false;
        }
      }
    }

    if ( is_array($model->data['links']) && !empty($model->data['links']) ){
      foreach ($model->data['links'] as $l ){
        if ( is_file($path.$l['image']) &&
          !$notes->addMedia(
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
      $model->getModel($model->pluginUrl('appui-ide').'/data_cache', [
        'deleteCache' => 'bbn/appui/grid',
        'deleteContent' => 0
      ]);
      return [
        'success' => true
      ];
    }
  }
}
