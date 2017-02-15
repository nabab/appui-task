<?php
/*
 * Describe what it does or you're a pussy
 *
 **/

/** @var $model \bbn\mvc\model*/
$s =& $model->inc->session;
$pm = new \bbn\appui\tasks($model->db);
return [
  'root' => $model->data['root'],
  'roles' => \bbn\appui\tasks::get_options_ids('roles'),
  'states' => \bbn\appui\tasks::get_options_ids('states'),
  'options' => [
    'states' => \bbn\appui\tasks::get_options_text_value('states'),
    'roles' => \bbn\appui\tasks::get_options_text_value('roles'),
    'cats' => \bbn\appui\tasks::cat_correspondances()
  ],
  'categories' => \bbn\appui\tasks::get_options_tree('cat'),
  'lng' => [
    'title' => _("Title"),
    'type' => _("Type"),
    'role' => _("Role"),
    'state' => _("State"),
    'duration' => _("Duration"),
    'inconnue' => _("Unknown"),
    'priority' => _("Priority"),
    'start' => _("Start"),
    'last' => _("Last"),
    'dead' => _("Deadline"),
    'see_task' => _("See task"),
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
