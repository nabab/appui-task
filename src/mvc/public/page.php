<?php
/*
 * Describe what it does to show you're not that dumb!
 *
 **/

/** @var $ctrl \bbn\Mvc\Controller */
if ( strpos(BBN_BASEURL, APPUI_TASKS_ROOT . 'page/') !== 0 ){
  $ctrl->obj->url = APPUI_TASKS_ROOT . 'page';
  $ctrl
    ->setColor('#000', '#FFF')
    ->setIcon('nf nf-fa-bug')
    ->combo(_("Tasks"), true);
}
