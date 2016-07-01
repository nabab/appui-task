<?php
/*
 * Describe what it does to show you're not that dumb!
 *
 **/

/** @var $this \bbn\mvc\controller */
if ( !isset($this->post['search']) ){
  $this->combo('<i class="fa fa-home appui-lg" title="'._("New task").' / '._("Search").'"> </i>', [
    'root' => $this->data['root'],
    'lng' => $this->get_model('./lng/search')
  ]);
}
else{
  $this->obj->data = $this->get_model($this->post);
}