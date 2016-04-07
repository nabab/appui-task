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
  if ( !empty($obj->data) ){
    $this->add_data($obj->data);
  }
  $this->data['my_ongoing_tasks'] = _("My ongoing tasks");
  $this->data['timeline'] = _("Timeline");
  $this->data['search'] = _("Search");
}
$this->combo(_("Projects"), $this->data);
