<?php
/*
 * Describe what it does to show you're not that dumb!
 *
 **/

/** @var $this \bbn\mvc\controller */
if ( !isset($this->arguments[0]) ){
  $this->add_data([
    'cat' => '0',
    'is_admin' => $this->inc->user->is_admin(),
    'lng' => [
      'problem_while_moving' => _("Sorry, a problem occured while moving this item, and although the tree says otherwise the item has not been moved."),
      'please_refresh' => _("Please refresh the tab in order to see the awful truth..."),
      'confirm_move' => _("Are you sure you want to move this task?")
    ]
  ]);
  $this->combo("Tasks' tree", $this->data);
}
else{
  $this->obj->data = $this->get_model(['id' => $this->arguments[0]]);
}
