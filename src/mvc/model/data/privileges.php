<?php
if (($manager = $model->inc->user->getManager())
  && ($activeUsers = $manager->getUsers())
  && ($privileges = $model->inc->options->items('privileges', 'task', 'appui'))
  && !empty($model->data['filters']['conditions'])
  && ($idPrivilege = \bbn\X::getField($model->data['filters']['conditions'], ['field' => 'id'], 'value'))
  && in_array($idPrivilege, $privileges, true)
) {
  $users = [];
  foreach ($activeUsers as $user) {
    if ($model->inc->pref->userHas($model->inc->perm->optionToPermission($idPrivilege, true), $user)) {
      if ($name = \bbn\X::getField($model->data['filters']['conditions'], ['field' => 'username'], 'value')) {
        if (($username = $manager->getName($user))
          && str_contains(strtolower($username), strtolower($name))
        ) {
          $users[] = $user;
        }
      }
      else {
        $users[] = $user;
      }
    }
  }
  return [
    'success' => true,
    'data' => $users,
    'total' => count($users)
  ];
}
return [
  'success' => false
];
