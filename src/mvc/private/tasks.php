<?php
/*
 * Describe what it does to show you're not that dumb!
 *
 **/

/** @var $this \bbn\mvc\controller */
if ( isset($this->arguments[0]) && ($this->arguments[0] === 'tasks') ){
  array_shift($this->arguments);
}
if ( !empty($this->arguments) ){
  $this->add_data([
    'is_template' => true,
    'id' => \bbn\str::get_numbers($this->arguments[0])
  ]);
  /** @todo false should be removed as last argument but idk why I need it */
  echo $this->get_view('', 'php', false);
  $this->obj->data = $this->get_model();
  if ( isset($this->obj->data['info']) ){
    $this->add_js()->set_title($this->obj->data['info']['title']);
  }
  $this->obj->url = implode("/", $this->arguments);
}
