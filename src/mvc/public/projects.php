<?php
/*
 * Describe what it does to show you're not that dumb!
 *
 **/

/** @var $this \bbn\mvc\controller */

// Needed for the tabNav!!
$this->obj->url = $this->say_dir().'/projects';
if ( $this->get_url() === $this->obj->url ){
  $this->obj->current = $this->say_dir().'/projects/ongoing';
}
$new = $this->add('./new');
if ( $obj = $new->get() ){
	$this->data['title'] = $obj->title;
  $this->data['content'] = $obj->output;
  $this->data['script'] = $obj->script;
}
$this->combo("Projects", $this->data);
