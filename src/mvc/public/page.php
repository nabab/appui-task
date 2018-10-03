<?php
/*
 * Describe what it does to show you're not that dumb!
 *
 **/

/** @var $ctrl \bbn\mvc\controller */

$ctrl->obj->url = APPUI_TASKS_ROOT . 'page';
$ctrl
  ->set_color('#000', '#FFF')
  ->set_icon('fas fa-bug')
  ->combo(_("Tasks"), true);