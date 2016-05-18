<?php
/*
 * Describe what it does to show you're not that dumb!
 *
 **/

/** @var $this \bbn\mvc\controller */
if ( !isset($this->post['id']) ){
  echo $this->set_title('<i class="fa fa-clock-o"> </i> &nbsp; _("Mes tâches en cours")')->add_js([
    'root' => $this->data['root'],
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
  $this->obj->tasks = $this->get_model($this->post);
}