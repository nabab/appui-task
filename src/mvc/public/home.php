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
  $this->combo(_("Projects"), [
    'root' => $this->data['root'],
    'options' => \bbn\appui\task::get_options(),
    'categories' => \bbn\appui\task::get_cats(),
    'lng' => [
      'opened_tasks' => _("Opened tasks"),
      'new_task' => _("New task"),
      'demo_task' => _("Demo task form"),
      'my_ongoing_tasks' => _("My ongoing tasks"),
      'timeline' => _("Timeline"),
      'search' => _("Search"),
      'soon' => _("Soon"),
      'all_tasks' => _("All tasks")
    ]
  ]);
}
else if ( !empty($this->arguments) ){
  $ctrl = $this->add('./'.implode($this->arguments, '/'), \bbn\x::merge_arrays($this->data, $this->post), true);
  $this->obj = $ctrl->get();
  if ( !isset($this->obj->url) ){
    $this->obj->url = implode($this->arguments, '/');
  }
}