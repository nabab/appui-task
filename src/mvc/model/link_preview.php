<?php
/*
 * Describe what it does or you're a pussy
 *
 **/

/** @var $model \bbn\mvc\model*/
if ( isset($model->data['url'], $model->data['ref']) && \bbn\str::is_url($model->data['url']) ){
  $linkPreview = new \LinkPreview\LinkPreview($model->data['url']);
  $parsed = $linkPreview->getParsed();
  $path = BBN_USER_PATH.'tmp/'.$model->data['ref'].'/';
  $root = strval(time());
  \bbn\file\dir::create_path($path.$root);
  foreach ($parsed as $parserName => $link) {
    if ( $parserName === 'general' ){
      $r = [
        'url' => $link->getUrl(),
        'realurl' => $link->getRealUrl(),
        'title' => $link->getPageTitle(),
        'desc' => $link->getDescription(),
        'pictures' => []
      ];
      $img = $link->getImage();
      $pictures = $link->getPictures();
      if ( !is_array($pictures) ){
        $pictures = [];
      }
      if ( !empty($img) ){
        array_unshift($pictures, $img);
      }
      foreach ( $pictures as $pic ){
        $saved = @file_get_contents($pic);
        if ( $saved && (strlen($saved) > 1000) ){
          $new = \bbn\str::encode_filename(basename($pic), \bbn\str::file_ext(basename($pic)));
          file_put_contents($path.$root.'/'.$new, $saved);
          unset($saved);
          $img = new \bbn\file\image($path.$root.'/'.$new);
          if ( $img->test() && ($imgs = $img->thumbs($path.$root)) ){
            array_push($r['pictures'], array_map(function($a) use($path){
              return substr($a, strlen($path));
            }, $imgs));
          }
        }
      }
      return $r;
    }
  }
}