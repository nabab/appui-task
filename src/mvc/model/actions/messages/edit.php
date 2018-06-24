<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 19/04/2018
 * Time: 13:03
 */
$notes = new \bbn\appui\notes($model->db);
if (
  !empty($model->data['id']) &&
  ($old = $notes->get($model->data['id']))
){
  $ok = true;
  $path = BBN_USER_PATH.'tmp/'.$model->data['ref'].'/';
  // Add the new note's version if the title|content is changed
  if (
    ($old['title'] !== $model->data['title']) ||
    ($old['content'] !== $model->data['text']) ||
    ($old['locked'] != $model->data['locked'])
  ){
    if ( $notes->update($model->data['id'], $model->data['title'] ?: '', $model->data['text'], null, $model->data['locked']) ){
      if (
        ($old['title'] !== $model->data['title']) ||
        ($old['content'] !== $model->data['text'])
      ){
        // Copy the files to the new version
        if ( !empty($model->data['files']) ){
          array_walk($model->data['files'], function($f) use($notes, $model, &$ok){
            if ( !empty($f['id']) ){
              if ( !$notes->media2version($f['id'], $model->data['id']) ){
                $ok = false;
              }
            }
          });
        }
        // Copy the links to the new version
        if ( !empty($model->data['links']) ){
          array_walk($model->data['links'], function($l) use($notes, $model, &$ok){
            if ( !empty($l['id']) ){
              if ( !$notes->media2version($l['id'], $model->data['id']) ){
                $ok = false;
              }
            }
          });
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
        if ( !$notes->add_media($model->data['id'], $path.$f['name']) ){
          $ok = false;
        }
      }
    });
  }
  // Add the new links
  if ( !empty($model->data['links']) ){
    array_walk($model->data['links'], function($l) use($notes, $model, &$ok, $path){
      if ( empty($l['id']) && is_file($path.$l['image']) ){
        if ( !$notes->add_media(
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
        (\bbn\x::find($model->data['files'], ['id' => $m['id']]) === false) &&
        (\bbn\x::find($model->data['links'], ['id' => $m['id']]) === false) &&
        !$notes->remove_media($m['id'], $model->data['id'])
      ){
        $ok = false;
      }
    });
  }

  return ['success' => $ok];
}
return ['success' => false];