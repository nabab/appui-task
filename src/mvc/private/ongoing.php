<?php
/*
 * Describe what it does to show you're not that dumb!
 *
 **/

/** @var $ctrl \bbn\mvc\controller */
if ( !isset($ctrl->post['id']) ){
  echo $ctrl->set_title('<i class="fa fa-clock-o"> </i> &nbsp; _("Mes tâches en cours")')->add_js([
    'root' => $ctrl->data['root'],
    'lng' => [
      'title' => _("Title"),
      'type' => _("Type"),
      'state' => _("State"),
      'duration' => _("Duration"),
      'unknown' => _("Unknown")
      'start' => _("Start"),
      'last'=> _("Last"),
      'deadline' => _("Deadline")
      'see_task' => _("See task"), // dichiarato in js inserire stringa


    ]
  ])->get_view();
}
else{
  $ctrl->obj->tasks = $ctrl->get_model($ctrl->post);
}