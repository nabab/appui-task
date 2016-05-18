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
    'unknown' => _("Unknown"),
    'start' => _("Start"),
    'last' => _("Last"),
    'title' => _("Title")
  ]
])->get_view();
