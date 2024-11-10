<?php
/*
 * Describe what it does or you're a pussy
 *
 **/

/** @var bbn\Mvc\Model $model */

if ( isset($model->data['url'], $model->data['ref']) && \bbn\Str::isUrl($model->data['url']) ){
  $linkPreview = new \LinkPreview\LinkPreview($model->data['url']);
  $parsed = $linkPreview->getParsed();
  $path = BBN_USER_PATH.'tmp/'.$model->data['ref'].'/';
  $root = \strval(time());
  \bbn\File\Dir::createPath($path.$root);

  foreach ($parsed as $parserName => $link) {
    if ( $parserName === 'general' ){
      $r = [
        'url' => $link->getUrl(),
        'realurl' => $link->getRealUrl(),
        'title' => $link->getTitle(),
        'desc' => $link->getDescription(),
        'picture' => ''
      ];
      $img = $link->getImage();
      $pictures = $link->getPictures();
      if ( !\is_array($pictures) ){
        $pictures = [];
      }
      if ( !empty($img) ){
        array_unshift($pictures, $img);
      }
      foreach ( $pictures as $pic ){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $pic);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $saved = curl_exec($curl);
        if ( $saved && (\strlen($saved) > 1000) ){
          $new = \bbn\Str::encodeFilename(basename($pic), \bbn\Str::fileExt(basename($pic)));
          file_put_contents($path.$root.'/'.$new, $saved);
          unset($saved);
          $img = new \bbn\File\Image($path.$root.'/'.$new);
          if ( $img->test() && ($img->getHeight() > 96) ){
            $img->resize(false, 96)->save();
            $r['picture'] = $root.'/'.$new;
            break;
          }
        }
      }
      return ['data' => $r];
    }
  }
}