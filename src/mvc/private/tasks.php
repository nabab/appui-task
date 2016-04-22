<?php
/*
 * Describe what it does to show you're not that dumb!
 *
 **/

/** @var $this \bbn\mvc\controller */
if ( empty($this->arguments) ){
  $this->data['is_template'] = false;
  $this->combo(_("Opened tasks"), [
    'root' => $this->data['root'],
    'lng' => []
  ]);
}
else{
  $this->data['is_template'] = true;
  $this->data['id'] = \bbn\str::get_numbers($this->arguments[0]);
  /** @todo false should be removed as last argument but idk why I need it */
  echo $this->get_view('', 'php', false);
  $this->obj->data = $this->get_model();
  if ( isset($this->obj->data['info']) ){
    $this->add_script('appui.tasks.create(data.info);');
    $this->set_title($this->obj->data['info']['title']);
  }
}