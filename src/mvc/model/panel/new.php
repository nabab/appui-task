<?php
/*
 * Describe what it does or you're a pussy
 *
 **/

/** @var $model \bbn\mvc\model */
$pm = new \bbn\appui\task($model->db);
$s =& $model->inc->session;
return [
  'ref' => time(),
  'lng' => [
    'confirm_delete' => _("Are you sure you want to remove yourself from this task??"),
    'file' => _("File"),
    'link' => _("Link"),
    'problem_file' => _("Problem with the file..."),
    'error_uploading' => _("Problem during the upload"),
    'file_exists' => _("A file with this name already exists"),
    'delete'=> _("Delete"),
  ],
  'roles' => [[
    "text" => _("Worker"),
    "value" => "worker"
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
  }, $model->get_model('usergroup/picker')),
  'categories' => $model->inc->options->map(function($a){
    $a['is_parent'] = !empty($a['items']);
    if ( $a['is_parent'] ){
      $a['expanded'] = true;
    }
    return $a;
  }, $pm->categories(), 1)
];
