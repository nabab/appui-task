<?php
use bbn\Str;

/** @var bbn\Mvc\Controller $ctrl */
if ($ctrl->getConstant('baseURL') && Str::pos($ctrl->getConstant('baseURL'), APPUI_TASKS_ROOT . 'page/') !== 0) {
  $ctrl->obj->url = APPUI_TASKS_ROOT . 'page';
  $ctrl
    ->setColor('#000', '#FFF')
    ->setIcon('nf nf-fa-bug')
    ->combo(_("Tasks"), true);
}
