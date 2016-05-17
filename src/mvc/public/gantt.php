<?php
/*
 * Describe what it does to show you're not that dumb!
 *
 **/

/** @var $this \bbn\mvc\controller */
echo $this->set_title("Gantt")->add_js([
  'root' => $this->data['root'],
  'lng' => [
    'priority' => _("Priority"),
    'type' => _("Type"),
    'state' => _("State"),
    'duration' => _("Duration"),
    'inconnue' => _("Inconnue"),
    'start' => _("Start"),
    'last' => _("Last"),
    'title' => _("Title"),
    'see_task' => _(" ") //inserire stringa  per linea di comando 114 gantt.js
  ]
])->get_view();
