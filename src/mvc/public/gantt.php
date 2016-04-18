<?php
/*
 * Describe what it does to show you're not that dumb!
 *
 **/

/** @var $this \bbn\mvc\controller */
echo $this->set_title("Gantt")->add_js([
  'root' => $this->data['root'],
  'lng' => []
])->get_view();