<?php
/*
 * Describe what it does to show you're not that dumb!
 *
 **/

/** @var $ctrl \bbn\mvc\controller */
if ( isset($ctrl->arguments[0]) && ($ctrl->arguments[0] === 'tasks') ){
  array_shift($ctrl->arguments);
}
if ( !empty($ctrl->arguments) ){
  $ctrl->add_data([
    'is_template' => true,
    'id' => $ctrl->arguments[0]
  ]);
  /** @todo false should be removed as last argument but idk why I need it */
  echo $ctrl->get_view('', 'php', false);
  $ctrl->obj->data = $ctrl->get_model();
  if ( isset($ctrl->obj->data['info']) ){
    $ctrl->add_js()->set_title($ctrl->obj->data['info']['title']);
  }
  $ctrl->obj->url = 'tasks/'.$ctrl->data['id'];
}
