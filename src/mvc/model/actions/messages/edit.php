<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 19/04/2018
 * Time: 13:03
 */
$notes = new \bbn\Appui\Note($model->db);
if (
  !empty($model->data['id']) &&
  ($old = $notes->getFull($model->data['id']))
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
      $vcs = new \bbn\Appui\Vcs($model->db);
      if ((($old['content'] !== $model->data['text'])
          || ($old['locked'] != $model->data['locked']))
        && ($vcsNote = $vcs->getAppuiTaskNoteByNote($model->data['id']))
        && ($vcsTask = $vcs->getAppuiTaskById($vcsNote['id_parent']))
      ) {
        try {
          $hasVcsToken = $vcs->getUserAccessToken($vcsTask['id_server']);
        }
        catch(\Exception $e) {
          $hasVcsToken = false;
        }
        if (!empty($hasVcsToken)) {
          $n = $notes->get($model->data['id']);
          $vcs->changeServer($vcsTask['id_server'])
            ->editProjectIssueComment(
              $vcsTask['id_project'],
              $vcsTask['id_issue'],
              $vcsNote['id_comment'],
              $model->data['text'],
              (bool)$model->data['locked'],
              $n['creation']
            );
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
        (\bbn\X::find($model->data['files'], ['id' => $m['id']]) === null) &&
        (\bbn\X::find($model->data['links'], ['id' => $m['id']]) === null) &&
        !$notes->removeMedia($m['id'], $model->data['id'])
      ){
        $ok = false;
      }
    });
  }

  return ['success' => $ok];
}
return ['success' => false];