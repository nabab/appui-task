<?php

if ($model->hasData(['idPrivilege', 'users'], true)
  && ($manager = $model->inc->user->getManager())
  && ($idPerm = $model->inc->perm->optionToPermission($model->data['idPrivilege']))
) {
  $did = 0;
  foreach ($model->data['users'] as $user) {
    $did += $manager->addPermission($idPerm, $user);
  }
  return [
    'success' => $did === count($model->data['users'])
  ];
}
return [
  'success' => false
];
