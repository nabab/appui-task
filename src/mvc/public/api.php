<?php

if (!empty($ctrl->post['action'])
  && ($idUser = $ctrl->inc->user->getId())
) {
  $tasks = new \bbn\Appui\Task($ctrl->db);
  $tasks->setUser($idUser);
  $d = [];
  $success = false;
  switch ($ctrl->post['action']) {
    case 'ping':
      $success = true;
      break;
    case 'getList':
      $m = $ctrl->getModel($ctrl->pluginUrl('appui-task').'/data/list', [
        'limit' => 0,
        'filters' => [
          'conditions' => [[
            'field' => 'bbn_tasks.state',
            'value' => $tasks->idState('ongoing')
          ]]
        ],
        'order' => [[
          'field' => 'bbn_notes_versions.title',
          'dir' => 'ASC'
        ]]
      ]);
      $d['list'] = $m['data'];
      $success = true;
      break;

    case 'startTrack':
      if (!empty($ctrl->post['id'])) {
        $success = $tasks->startTrack($ctrl->post['id']);
      }
      break;

    case 'stopTrack':
      if (!empty($ctrl->post['id'])) {
        $success = $tasks->stopTrack($ctrl->post['id'], $ctrl->post['message'] ?? false);
      }
      break;

    case 'switchTracker':
      if (!empty($ctrl->post['current'])
        && !empty($ctrl->post['new'])
      ) {
        $success = $tasks->switchTracker($ctrl->post['current'], $ctrl->post['new'], $ctrl->post['message'] ?? false);
      }
      break;
  }
  $ctrl->obj->success = $success;
  if ($d['active'] = $tasks->getActiveTrack()) {
    if ($tasks->isTokensActive()
      && ($tokensCfg = $tasks->getTokensCfg())
      && ($tokenCat = $tasks->getTokensCategory($d['active']['id_task']))
    ) {
      $currentToken = (time() - strtotime($d['active']['start'])) / $tokensCfg['step'];
      $d['active']['tokens'] = ceil($currentToken + $tasks->calcLinkedTokens($d['active']['id_task']));
      $d['active']['taskTokens'] = ceil($tasks->getTokens($d['active']['id_task']) + $d['active']['tokens']);
      $tokens = $tasks->getTokensCurrent($tokenCat);
      $d['active']['availableTokens'] = !empty($tokens['available']) ? ($tokens['available'] - $d['active']['tokens']) : 0;
      $d['active']['totalTokens'] = $tokens['total'];
    }
  }

  $ctrl->obj->data = $d;
}
else {
  $ctrl->obj->success = false;
}