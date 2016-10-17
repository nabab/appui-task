<?php
/*
 * Describe what it does or you're a pussy
 *
 **/

/** @var $model \bbn\mvc\model*/
$s =& $model->inc->session;
$pm = new \bbn\appui\task($model->db);
return [
  'root' => $model->data['root'],
  'roles' => \bbn\appui\task::get_roles(),
  'states' => \bbn\appui\task::get_states(),
  'options' => \bbn\appui\task::get_options(),
  'categories' => \bbn\appui\task::get_cats(),
  'lng' => [
    'new_task_search' => 'Nouvelle tâche / Recherche',
    'no_role_permission' => _("You have no right to modify the roles in this task"),
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
    'file_exists' => _("A file with this name already exists"),
    'user' => _("User"),
    'date' => _("Date"),
    'action' => _("Action"),
    'global_view' => _("Global view"),
    'roles' => _("Roles"),
    'journal' => _("Events journal"),
    'no_comment_text' => _("You have to enter a comment, a link, or a file"),
    'sure_to_hold' => _("Are you sure you want to put this task on hold?"),
    'sure_to_resume' => _("Are you sure you want to resume this task?"),
    'sure_to_close' => _("Are you sure you want to close this task?"),
    'sure_to_unfollow' => _("Are you sure you want to unfollow this task?"),
  ],
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
