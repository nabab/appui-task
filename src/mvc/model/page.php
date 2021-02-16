<?php
/*
 * Describe what it does or you're a pussy
 *
 **/

/** @var $model \bbn\Mvc\Model*/
$s =& $model->inc->session;
$pm = new \bbn\Appui\Task($model->db);
$mgr = $model->inc->user->getManager();
$arch = $model->inc->user->getClassCfg()['arch']['groups'];
$groups = $mgr->groups();
\bbn\X::sortBy($groups, $arch['group'], 'ASC');
return [
  'root' => APPUI_TASKS_ROOT,
  'root_notes' => $model->pluginUrl('appui-note').'/',
  'roles' => \bbn\Appui\Task::getAppuiOptionsIds('roles'),
  'states' => \bbn\Appui\Task::getAppuiOptionsIds('states'),
  'options' => [
    'states' => \bbn\Appui\Task::getAppuiOptionsTextValue('states'),
    'roles' => \bbn\Appui\Task::getAppuiOptionsTextValue('roles'),
    'cats' => \bbn\Appui\Task::catCorrespondances()
  ],
  'categories' => \bbn\Appui\Task::getOptionsTree('cats'),
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
  }, $pm->categories(), 1),
  'media_types' => $model->inc->options->codeOptions(\bbn\Appui\Note::getAppuiOptionId('media'))
];
