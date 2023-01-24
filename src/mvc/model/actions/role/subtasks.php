<?php
if ($model->hasData('id', true)) {
  $taskCls = new \bbn\Appui\Task($model->db);
  if (($children = $taskCls->getChildren($model->data['id']))
    && ($roles = $taskCls->infoRoles($model->data['id']))
  ) {
    $success = true;
    $newRoles = [];
    foreach ($children as $child) {
      foreach ($child['roles'] as $role => $users) {
        foreach ($users as $user) {
          if (!isset($roles[$role]) || !in_array($user, $roles[$role])) {
            $taskCls->removeRole($child['id'], $user);
          }
        }
      }
      foreach ($roles as $role => $users) {
        foreach ($users as $user) {
          if (!$taskCls->hasRole($child['id'], $user)
            && (!isset($child[$role])
              || !in_array($user, $child[$role]))
          ) {
            $taskCls->addRole($child['id'], $role, $user);
          }
        }
      }
      if ($roles !== ($childRoles = $taskCls->infoRoles($child['id']))) {
        $success = false;
      }
      $newRoles[$child['id']] = $childRoles;
    }
    return [
      'success' => $success,
      'roles' => $newRoles
    ];
  }
}
return ['success' => false];
