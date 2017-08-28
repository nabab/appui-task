<?php
/*
 * Describe what it does to show you're not that dumb!
 *
 **/

/** @var $ctrl \bbn\mvc\controller */
if ( isset($ctrl->files['qqfile'], $ctrl->arguments[0]) &&
        \bbn\str::is_integer($ctrl->arguments[0]) ){
  $f =& $ctrl->files['qqfile'];
  $path = BBN_USER_PATH.'tmp/'.$ctrl->arguments[0];
  $new = \bbn\str::encode_filename($f['name'], \bbn\str::file_ext($f['name']));
  $file = $path.'/'.$new;
  if ( \bbn\file\dir::create_path($path) &&
    move_uploaded_file($f['tmp_name'], $file) ){
    $tmp = \bbn\str::file_ext($new, 1);
    $fname = $tmp[0];
    $ext = $tmp[1];
    $ctrl->obj->success = 1;
    $archives = ['zip', 'rar', 'tar', 'gzip', 'iso'];
    $images = ['jpg','gif','jpeg','png','svg'];
    $files = [basename($file)];
    if ( in_array($ext, $archives) ){
      $archive = \wapmorgan\UnifiedArchive\UnifiedArchive::open($file);
      \bbn\file\dir::create_path($path.'/'.$fname);
      if ( $num = $archive->extractNode($path.'/'.$fname, '/') ){
        $tmp = getcwd();
        chdir($path);
        $all = \bbn\file\dir::scan($fname, 'file');
        foreach ( $all as $a ){
          array_push($files, $a);
        }
        chdir($tmp);
      }
    }
    $ctrl->obj->files = [];
    foreach ( $files as $f ){
      $tmp = \bbn\str::file_ext($f, 1);
      $fname = $tmp[0];
      $ext = $tmp[1];
      $res = [
        'name' => $f,
        'size' => filesize($path.'/'.$f),
        'extension' => '.'.$ext
      ];
      /*if ( in_array($ext, $images) ){
        // Creating thumbnails
        $res['imgs'] = [];
        $img = new \bbn\file\image($path.'/'.$f);
        if ( $img->test() && ($imgs = $img->thumbs($path)) ){
          array_push($res['imgs'], array_map(function($a) use($path){
            return substr($a, strlen($path)+1);
          }, $imgs));
        }
        $res['imgs']['length'] = count($res['imgs']);
      }*/
      array_push($ctrl->obj->files, $res);
    }
  }
}
if ( !isset($ctrl->obj->success) ){
  $ctrl->obj->success = 0;
}
