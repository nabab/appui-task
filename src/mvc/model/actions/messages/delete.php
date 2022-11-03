<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 19/04/2018
 * Time: 13:03
 */
if (
  !empty($model->data['id']) &&
  \bbn\Str::isUid($model->data['id'])
){
  $notes = new \bbn\Appui\Note($model->db);
  if ( $notes->remove($model->data['id'], true) ){

    $vcs = new \bbn\Appui\Vcs($model->db);
    if (($vcsNote = $vcs->getAppuiTaskNoteByNote($model->data['id']))
      && ($vcsTask = $vcs->getAppuiTaskById($vcsNote['id_parent']))
    ) {
      try {
        $hasVcsToken = $vcs->getUserAccessToken($vcsTask['id_server']);
      }
      catch(\Exception $e) {
        $hasVcsToken = false;
      }
      if (!empty($hasVcsToken)) {
        $vcs->changeServer($vcsTask['id_server']);
        $vcs->addToTasksQueue($vcsTask['id_project'], 'export', [
          'type' => 'comment',
          'action' => 'delete',
          'idUser' => $model->inc->user->getId(),
          'idIssue' => $vcsTask['id_issue'],
          'idNote' => $model->data['id'],
          'idComment' => $vcsNote['id_comment']
        ]);
      }
    }

    /** @todo To remove this and add an apposite function in grid */
    $model->getModel($model->pluginUrl('appui-ide').'/data_cache', [
      'deleteCache' => 'bbn/appui/grid',
      'deleteContent' => 0
    ]);
    return [
      'success' => true
    ];
  }
}
return [
  'success' => false
];