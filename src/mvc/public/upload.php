<?php
/*
 * Describe what it does to show you're not that dumb!
 *
 **/

/** @var $ctrl \bbn\Mvc\Controller */

if ( isset($ctrl->files['filename'], $ctrl->arguments[0]) &&
  \bbn\Str::isInteger($ctrl->arguments[0])
){
  $f =& $ctrl->files['filename'];
  $path = BBN_USER_PATH.'tmp/'.$ctrl->arguments[0];
  $new = \bbn\Str::encodeFilename($f['name'], \bbn\Str::fileExt($f['name']));
  $file = $path.'/'.$new;
  if ( \bbn\File\Dir::createPath($path) &&
    move_uploaded_file($f['tmp_name'], $file) ){
    $tmp = \bbn\Str::fileExt($new, 1);
    $fname = $tmp[0];
    $ext = $tmp[1];
    $ctrl->obj->success = 1;
    $archives = ['zip', 'rar', 'tar', 'gzip', 'iso'];
    $images = ['jpg','gif','jpeg','png','svg'];
    $files = [basename($file)];
    if ( \in_array($ext, $archives) ){
      $archive = \wapmorgan\UnifiedArchive\UnifiedArchive::open($file);
      \bbn\File\Dir::createPath($path.'/'.$fname);
      if ( $num = $archive->extractNode($path.'/'.$fname, '/') ){
        $tmp = getcwd();
        chdir($path);
        $all = \bbn\File\Dir::scan($fname, 'file');
        foreach ( $all as $a ){
          array_push($files, $a);
        }
        chdir($tmp);
      }
    }
    $ctrl->obj->files = [];
    foreach ( $files as $f ){
      $tmp = \bbn\Str::fileExt($f, 1);
      $fname = $tmp[0];
      $ext = $tmp[1];
      $res = [
        'name' => $f,
        'size' => filesize($path.'/'.$f),
        'extension' => '.'.$ext
      ];
      if ( \in_array($ext, $images) ){
        // Creating thumbnails
        $res['imgs'] = [];
        $img = new \bbn\File\Image($path.'/'.$f);
        if ( $img->test() && ($imgs = $img->thumbs($path)) ){
          array_push($res['imgs'], array_map(function($a) use($path){
            return substr($a, \strlen($path));
          }, $imgs));
        }
        $res['imgs']['length'] = \count($res['imgs']);
      }
      array_push($ctrl->obj->files, $res);
    }
  }
}