<?php
/*
 * Describe what it does to show you're not that dumb!
 *
 **/

/** @var $ctrl \bbn\mvc\controller */
if ( !isset($ctrl->arguments[0]) ){
  $ctrl->add_data([
    'cat' => '0',
    'is_admin' => $ctrl->inc->user->is_admin(),
    'lng' => [
      'problem_while_moving' => _("Sorry, a problem occured while moving this item, and although the tree says otherwise the item has not been moved."),
      'please_refresh' => _("Please refresh the tab in order to see the awful truth..."),
      'confirm_move' => _("Are you sure you want to move this task?")
    ]
  ]);
  $ctrl->combo("Tasks' tree", $ctrl->data);
}
else{
  $ctrl->obj->data = $ctrl->get_model(['id' => $ctrl->arguments[0]]);
}
