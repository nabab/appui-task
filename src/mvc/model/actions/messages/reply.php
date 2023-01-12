<?php

use bbn\Appui\Note;

if ($model->hasData(['ref', 'text', 'id_parent', 'id_alias', 'id_task'], true)
  && ($id_type = $model->inc->options->fromCode('tasks', 'types', 'note', 'appui'))
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

    $taskCls = new \bbn\Appui\Task($model->db);
    $taskCls->addLog($model->data['id_task'], 'comment_reply');

    $path = $model->userTmpPath() . $model->data['ref'].'/';
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

    $vcs = new \bbn\Appui\Vcs($model->db);
    if ($vcsTask = $vcs->getAppuiTaskByTask($model->data['id_task'])) {
      $n = $notes->get($id_note);
      $vcs->addToTasksQueue($vcsTask['id_project'], 'export', [
        'type' => 'comment',
        'action' => 'insert',
        'idUser' => $model->inc->user->getId(),
        'idIssue' => $vcsTask['id_issue'],
        'idNote' => $id_note,
        'text' => $model->data['text'],
        'locked' => empty($model->data['locked']) ? 0 : 1,
        'created' => $n['creation'],
        'updated' => $n['creation']
      ], $vcsTask['id_server']);
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
