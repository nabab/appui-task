<?php

if ($model->hasData(['idUser', 'idPrivilege'], true)
  && ($manager = $model->inc->user->getManager())
  && ($idPerm = $model->inc->perm->optionToPermission($model->data['idPrivilege']))
) {
  return [
    'success' => $manager->removePermission($idPerm, $model->data['idUser'])
  ];
}
return ['success' => false];
