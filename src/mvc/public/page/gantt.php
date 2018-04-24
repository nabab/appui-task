<?php
/*
 * Describe what it does to show you're not that dumb!
 *
 **/

/** @var $ctrl \bbn\mvc\controller */
echo $ctrl->set_title("Gantt")->add_js([
  'root' => $ctrl->data['root'],
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
