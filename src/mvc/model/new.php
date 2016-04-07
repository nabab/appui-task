<?php
/*
 * Describe what it does or you're a pussy
 *
 **/

/** @var $this \bbn\mvc\model */
$pm = new \bbn\appui\task($this->db, $this->inc->user, $this->inc->options);
$s =& $this->inc->session;
return [
  'confirm_delete' => _("Are you sure you want to remove yourself from this task??"),
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
    if ( $a['id'] !== $s->get('id_group') ){
      $a['expanded'] = false;
    }
    return $a;
  }, $this->get_model('usergroup/picker')),
  'categories' => $pm->categories()
];