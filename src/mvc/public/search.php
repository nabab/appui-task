<?php
/*
 * Describe what it does to show you're not that dumb!
 *
 **/

/** @var $this \bbn\mvc\controller */

if ( isset($this->post['search']) ){
  $this->obj->data = $this->get_model($this->post);
}
else{
  $this->combo("Recherche");
}
