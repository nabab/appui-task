<?php
/*
 * Describe what it does or you're a pussy
 *
 **/

/** @var bbn\Mvc\Model $model */

use \bbn\Appui\Task;
use \bbn\Appui\Note;
use \bbn\Appui\Dashboard;
use \bbn\X;

$s =& $model->inc->session;
$taskCls = new Task($model->db);
$mgr = $model->inc->user->getManager();
$arch = $model->inc->user->getClassCfg()['arch']['groups'];
$groups = $mgr->groups();
$d = [
  'root' => APPUI_TASKS_ROOT,
  'root_notes' => $model->pluginUrl('appui-note').'/',
  'roles' => Task::getOptionsIds('roles'),
  'states' => Task::getOptionsIds('states'),
  'options' => [
    'states' => array_map(function($s){
      return X::mergeArrays($s, Task::getOptionsObject()->getValue($s['value']) ?: []);
    }, Task::getOptionsTextValue('states')),
    'roles' => array_map(function($r){
      return X::mergeArrays($r, Task::getOptionsObject()->getValue($r['value']) ?: []);
    }, Task::getOptionsTextValue('roles')),
    'cats' => Task::catCorrespondances()
  ],
  'usergroup' => $model->inc->user->getGroup(),
  'groups' => array_map(function($g) use($arch){
    $g['text'] = $g[$arch['group']];
    unset($g[$arch['group']]);
    return $g;
  }, $groups),
  'categories' => $model->inc->options->map(function($a){
    $a['is_parent'] = !empty($a['items']);
    if ( $a['is_parent'] ){
      $a['expanded'] = true;
    }
    return $a;
  }, $taskCls->categories(), 1),
  'media_types' => $model->inc->options->codeOptions(Note::getOptionId('media')),
  'dashboard' => [
    'widgets' => [],
    'order' => []
  ],
  'privileges' => array_map(function($p) use($model){
    return $model->inc->perm->has($model->inc->perm->optionToPermission($p, true));
  }, $model->inc->options->codeIds('privileges', 'task', 'appui')),
  'tokens' => [
    'active' => $taskCls->isTokensActive(),
    'cfg' => $taskCls->getTokensCfg()
  ]
];

try {
  $dashboard = new Dashboard('appui-task');
  if (($widgets = $dashboard->getUserWidgetsCode())) {
    $d['dashboard']['widgets'] = $widgets;
    $d['dashboard']['order'] = $dashboard->getOrder($widgets);
  }
}
catch (Exception $e) {
  return ['error' => $e->getMessage()];
}
X::sortBy($groups, $arch['group'], 'ASC');
return $d;
