<?php
/*
 * Describe what it does to show you're not that dumb!
 *
 **/

/** @var $ctrl \bbn\mvc\controller */
$ctrl->obj->tasks = $ctrl->get_model($ctrl->post);
