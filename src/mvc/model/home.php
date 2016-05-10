<?php
/*
 * Describe what it does or you're a pussy
 *
 **/

/** @var $this \bbn\mvc\model*/
$s =& $this->inc->session;
$pm = new \bbn\appui\task($this->db);
return [
  'root' => $this->data['root'],
  'options' => \bbn\appui\task::get_options(),
  'categories' => \bbn\appui\task::get_cats(),
  'priority_colors' => ['#F00', '#F40', '#F90', '#FC0', '#FF0', '#AD0', '#5B0', '#090', '#CCC', '#FFF'],
  'lng' => [
    'opened_tasks' => _("Opened tasks"),
    'new_task' => _("New task"),
    'demo_task' => _("Demo task form"),
    'my_ongoing_tasks' => _("My ongoing tasks"),
    'timeline' => _("Timeline"),
    'search' => _("Search"),
    'soon' => _("Soon"),
    'all_tasks' => _("All tasks"),
    'confirm_delete' => _("Are you sure you want to remove yourself from this task??"),
    'file' => _("File"),
    'link' => _("Link"),
    'problem_file' => _("Problem with the file..."),
    'error_uploading' => _("Problem during the upload"),
    'file_exists' => _("A file with this name already exists")
  ],
  'roles' => [[
    "text" => _("Worker"),
    "value" => "doer"
  ], [
    "text" => _("Manager"),
    "value" => "manager"
  ], [
    "text" => _("Spectator"),
    "value" => "viewer"
  ]],
  'groups' => array_map(function($a) use ($s){
    if ( $a['id'] !== $s->get('user', 'id_group') ){
      $a['expanded'] = false;
    }
    return $a;
  }, $this->get_model('usergroup/picker')),
  'categories' => $this->inc->options->map(function($a){
    $a['is_parent'] = !empty($a['items']);
    if ( $a['is_parent'] ){
      $a['expanded'] = true;
    }
    return $a;
  }, $pm->categories(), 1)
];
