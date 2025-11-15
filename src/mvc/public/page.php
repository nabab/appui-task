<?php
/*
 * Describe what it does to show you're not that dumb!
 *
 **/
use bbn\Str;

/** @var bbn\Mvc\Controller $ctrl */
if (defined('BBN_BASEURL') && Str::pos(BBN_BASEURL, APPUI_TASKS_ROOT . 'page/') !== 0) {
  $ctrl->obj->url = APPUI_TASKS_ROOT . 'page';
  $ctrl
    ->setColor('#000', '#FFF')
    ->setIcon('nf nf-fa-bug')
    ->combo(_("Tasks"), true);
}
