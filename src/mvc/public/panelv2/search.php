<?php
/*
 * Describe what it does to show you're not that dumb!
 *
 **/

/** @var $ctrl \bbn\mvc\controller */
if ( !isset($ctrl->post['search']) ){
  $ctrl->combo('<i class="fa fa-home bbn-lg" title="'._("New task").' / '._("Search").'"> </i>', [
    'root' => $ctrl->data['root'],
    'lng' => $ctrl->get_model('./lng/search')
  ]);
}
else{
  $ctrl->obj->data = $ctrl->get_model($ctrl->post);
}