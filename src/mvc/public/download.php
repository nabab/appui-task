<?php
/*
 * Describe what it does to show you're not that dumb!
 *
 **/
/** @var bbn\Mvc\Controller $ctrl */
if ( \count($ctrl->arguments) > 1 ){
  switch ( $ctrl->arguments[0] ){
    case 'media':
      if (
        ($m = new \bbn\Appui\Medias($ctrl->db)) &&
        ($media = $m->getMedia($ctrl->arguments[1]))
       ){
        $ctrl->obj->file = $media;
      }
      break;
  }
}
