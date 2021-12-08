<?php

/** @var $ctrl \bbn\Mvc\Controller */
if ( strpos($ctrl->baseURL, APPUI_TASKS_ROOT . 'page/') !== 0 ){
  $ctrl->obj->url = APPUI_TASKS_ROOT . 'page';
  $ctrl
    ->setColor('#000', '#FFF')
    ->setIcon('nf nf-fa-bug')
    ->combo(_("Tasks"), true);
}
