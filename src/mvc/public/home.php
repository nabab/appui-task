<?php
/*
 * Describe what it does to show you're not that dumb!
 *
 **/

/** @var $this \bbn\mvc\controller */

// Needed for the tabNav!!

/*
if ( $this->get_url() === $this->obj->url ){
  $this->obj->current = $this->say_dir().'/projects/ongoing';
}
$new = $this->add('./new');
if ( $obj = $new->get() ){
	$this->data['title'] = $obj->title;
  $this->data['content'] = $obj->content;
  $this->data['script'] = $obj->script;
  if ( !empty($obj->data) ){
    $this->add_data($obj->data);
  }
}
*/
if ( empty($this->post['appui_baseURL']) ){
  $this->combo('<i class="fa fa-bug"> </i> &nbsp; '._("Projects"), true);
  $this->obj->bcolor = '#000000';
  $this->obj->fcolor = '#FFFFFF';
}
else if ( !empty($this->arguments) ){
  $ctrl = $this->add('./'.implode($this->arguments, '/'), \bbn\x::merge_arrays($this->data, $this->post), true);
  $this->obj = $ctrl->get();
  if ( !isset($this->obj->url) ){
    $this->obj->url = implode($this->arguments, '/');
  }
}