<?php
/*
 * Describe what it does to show you're not that dumb!
 *
 **/

/** @var $ctrl \bbn\mvc\controller */

$ctrl->combo('<i class="fa fa-bug"> </i> &nbsp; '._("Projects"), true);
$ctrl->obj->bcolor = '#000000';
$ctrl->obj->fcolor = '#FFFFFF';
$ctrl->obj->url = APPUI_TASKS_ROOT . 'panel';