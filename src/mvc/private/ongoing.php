<?php
/*
 * Describe what it does to show you're not that dumb!
 *
 **/

/** @var $this \bbn\mvc\controller */
if ( !isset($this->post['id']) ){
  echo $this->set_title("Mes tâches en cours")->add_js([
    'root' => $this->data['root'],
    'lng' => []
  ])->get_view();
}
else{
  $this->obj->tasks = $this->get_model($this->post);
}