<?php
/*
 * Describe what it does to show you're not that dumb!
 *
 **/

/** @var $this \bbn\mvc\controller */
if ( !isset($this->post['search']) ){
  $this->combo('<i class="fa fa-home appui-lg" title="'._("New task").' / '._("Search").'"> </i>', [
    'root' => $this->data['root'],
    'lng' => [
      'title' => _("Title"),
      'type' => _("Type"),
      'role' => _("Role"),
      'state' => _("State"),
      'duration' => _("Duration"),
      'inconnue' => _("Unknown"),
      'priority' => _("Priority"),
      'start' => _("Start"),
      'last' => _("Last"),
      'dead' => _("Deadline"),
      'see_task' => _("See task"), //inserire stringa linea corispettiva 140 search.js

    ]
  ]);
}
else{
  $this->obj->data = $this->get_model($this->post);
}