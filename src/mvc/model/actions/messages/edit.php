<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 19/04/2018
 * Time: 13:03
 */
$notes = new \bbn\Appui\Note($model->db);
if (!empty($model->data['id'])
  && ($old = $notes->getFull($model->data['id']))
  && !empty($model->data['id_task'])
){
  $ok = true;
  $path = $model->userTmpPath() . $model->data['ref'].'/';
  // Add the new note's version if the title|content is changed
  if (
    ($old['title'] !== $model->data['title']) ||
    ($old['content'] !== $model->data['text']) ||
    ($old['locked'] != $model->data['locked'])
  ){
    if ( $notes->update($model->data['id'], $model->data['title'] ?: '', $model->data['text'], false, $model->data['locked']) ){
      if (
        ($old['title'] !== $model->data['title']) ||
        ($old['content'] !== $model->data['text'])
      ){
        // Copy the files to the new version
        if ( !empty($model->data['files']) ){
          array_walk($model->data['files'], function($f) use($notes, $model, &$ok){
            if ( !empty($f['id']) ){
              if ( !$notes->addMediaToNote($f['id'], $model->data['id']) ){
                $ok = false;
              }
            }
          });
        }
        // Copy the links to the new version
        if ( !empty($model->data['links']) ){
          array_walk($model->data['links'], function($l) use($notes, $model, &$ok){
            if ( !empty($l['id']) ){
              if ( !$notes->addMediaToNote($l['id'], $model->data['id']) ){
                $ok = false;
              }
            }
          });
        }
      }
      if ($model->hasPlugin('appui-vcs')) {
        $vcs = new \bbn\Appui\Vcs($model->db);
        if ((($old['content'] !== $model->data['text'])
            || ($old['locked'] != $model->data['locked']))
          && ($vcsNote = $vcs->getAppuiTaskNoteByNote($model->data['id']))
          && ($vcsTask = $vcs->getAppuiTaskById($vcsNote['id_parent']))
        ) {
          $n = $notes->get($model->data['id']);
          $vcs->addToTasksQueue($vcsTask['id_project'], 'export', [
            'type' => 'comment',
            'action' => 'update',
            'idUser' => $model->inc->user->getId(),
            'idIssue' => $vcsTask['id_issue'],
            'idNote' => $model->data['id'],
            'idComment' => $vcsNote['id_comment'],
            'text' => $model->data['text'],
            'locked' => empty($model->data['locked']) ? 0 : 1,
            'created' => $n['creation'],
            'updated' => $n['creation']
          ], $vcsTask['id_server']);
        }
      }
    }
    else {
      $ok = false;
    }
  }
  // Add the new files
  if ( !empty($model->data['files']) ){
    array_walk($model->data['files'], function($f) use($notes, $model, &$ok, $path){
      if ( empty($f['id']) && is_file($path.$f['name']) ){
        if ( !$notes->addMedia($model->data['id'], $path.$f['name']) ){
          $ok = false;
        }
      }
    });
  }
  // Add the new links
  if ( !empty($model->data['links']) ){
    array_walk($model->data['links'], function($l) use($notes, $model, &$ok, $path){
      if ( empty($l['id']) && is_file($path.$l['image']) ){
        if ( !$notes->addMedia(
          $model->data['id'],
          $path.$l['image'],
          json_encode([
            'url' => $l['content']['url'],
            'description' => $l['content']['description']
          ]),
          $l['title'],
          'link'
        ) ){
          $ok = false;
        }
      }
    });
  }
  // Remove deleted medias
  if ( !empty($old['medias']) ){
    array_walk($old['medias'], function($m) use($model, &$ok, $notes){
      if (
        (\bbn\X::search($model->data['files'], ['id' => $m['id']]) === null) &&
        (\bbn\X::search($model->data['links'], ['id' => $m['id']]) === null) &&
        !$notes->removeMedia($m['id'], $model->data['id'])
      ){
        $ok = false;
      }
    });
  }

  if ($ok) {
    $taskCls = new \bbn\Appui\Task($model->db);
    $taskCls->addLog($model->data['id_task'], 'comment_update');
  }

  return ['success' => $ok];
}
return ['success' => false];