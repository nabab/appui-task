<?php
/*
 * Describe what it does to show you're not that dumb!
 *
 **/
/** @var $ctrl \bbn\mvc\controller */
if ( \count($ctrl->arguments) > 1 ){
  switch ( $ctrl->arguments[0] ){
    case 'media':
      if (
        ($m = new \bbn\appui\medias($ctrl->db)) &&
        ($media = $m->get_media($ctrl->arguments[1]))
       ){
        $ctrl->obj->file = $media;
      }
      break;
  }
}
