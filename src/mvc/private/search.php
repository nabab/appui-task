<?php
/*
 * Describe what it does to show you're not that dumb!
 *
 **/

/** @var $this \bbn\mvc\controller */
if ( !isset($this->post['search']) ){
  $this->combo(_("New task").' / '._("Search"), [
    'root' => $this->data['root'],
    'lng' => []
  ]);
}
else{
  $this->obj->data = $this->get_model($this->post);
}