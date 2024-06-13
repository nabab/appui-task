<?php
if (isset($ctrl->post['limit'])) {
  if ($d = $ctrl->getModel(['id_user' => $ctrl->inc->user->getId()])) {
    $d = !empty($d['data']['list']) ? $d['data']['list'] : [];
    $ctrl->obj->success = true;
    $ctrl->obj->data = $d;
    $ctrl->obj->total = count($d);
  }
}
else {
  $ctrl->action();
}
