<?php
/** @var bbn\Mvc\Controller $ctrl */
if (!empty($ctrl->files['file'])
  && !empty($ctrl->arguments[0])
  && \bbn\Str::isInteger($ctrl->arguments[0])
){
  $f =& $ctrl->files['file'];
  $path = $ctrl->userTmpPath() . $ctrl->arguments[0];
  $new = !empty($_REQUEST['name']) && ($_REQUEST['name'] !== $f['name']) ?
    \bbn\Str::encodeFilename($_REQUEST['name'], \bbn\Str::fileExt($_REQUEST['name'])) :
    \bbn\Str::encodeFilename($f['name'], \bbn\Str::fileExt($f['name']));

  if (\bbn\File\Dir::createPath($path)
    && rename($f['tmp_name'], $path . '/' . $new)
  ){
    $ctrl->obj->success = 1;
    $ctrl->obj->data = [
      'file' => [
        'name' => $new,
        'size' => filesize($path.'/'.$new),
        'extension' => '.'.\bbn\Str::fileExt($new)
      ]
    ];
  }
}