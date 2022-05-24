<?php
$projectToken = '';
$userToken = '';
$host = '';
$projectID = null;

if (!empty($projectToken)
  && !empty($userToken)
  && !empty($host)
  && !empty($projectID)
) {
  $gitlab = new \bbn\Api\GitLab($projectToken, $host);
  $task = new \bbn\Appui\Task($ctrl->db);
  $notes = new \bbn\Appui\Note($ctrl->db);
  $notesCfg = $notes->getClassCfg();
  $notesFields = $notesCfg['arch']['notes'];
  $notesVersionsFields = $notesCfg['arch']['versions'];
  $usersCfg = $ctrl->inc->user->getClassCfg();
  $usersFields = $usersCfg['arch']['users'];
  $idTypeNote = $ctrl->inc->options->fromCode('tasks', 'types', 'note', 'appui');
  $did = 0;

  $getUserID = function($email) use($ctrl, $usersCfg, $usersFields){
    if (!($userID = $ctrl->db->selectOne($usersCfg['table'], $usersFields['id'], [
        $usersFields['email'] => $email,
        $usersFields['active'] => 1
      ]))
      && defined('BBN_EXTERNAL_USER_ID')
    ) {
      $userID = BBN_EXTERNAL_USER_ID;
    }
    return $userID;
  };

  if ($issues = $gitlab->getIssues($projectID)) {
    foreach ($issues as $issue) {
      if (!empty($issue->id)) {
        if ($t = $ctrl->db->select('bbn_tasks', [], ['id_git' => $issue->id])) {
          $idTask = $t->id;
        }
        else {
          $author = $gitlab->getUser($issue->author->id);
          if (!empty($author['email'])) {
            if ($userID = $getUserID($author['email'])) {
              $task->setUser($userID);
              $task->setDate(date('Y-m-d H:i:s', strtotime($issue->created_at)));
              if ($idTask = $task->insert([
                'title' => $issue->title,
                'type' => $ctrl->inc->options->fromCode('support', 'cats', 'task', 'appui'),
                'state' => $ctrl->inc->options->fromCode($issue->state, 'states', 'task', 'appui')
              ])) {
                $ctrl->db->update('bbn_tasks', ['id_git' => $issue->id], ['id' => $idTask]);
                $did++;
              }
            }
          }
        }
        if (!empty($idTask)
          && (!empty($issue->user_notes_count))
        ) {
          $issueNotes = $gitlab->getIssueNotes($projectID, $issue->iid);
          if (!empty($issueNotes)) {
            foreach ($issueNotes as $note) {
              if ($idNote = $ctrl->db->selectOne('bbn_tasks_notes', 'id_note', [
                'id_git' => $note->id,
                'id_task' => $idTask
                ])) {
                $n = $notes->get($idNote);
                if ($n[$notesVersionsFields['content']] !== $note->body) {
                  $notes->update($idNote, '', $note->body);
                }
              }
              else {
                $author = $gitlab->getUser($note->author->id);
                if (!empty($author['email'])
                  && ($userID = $getUserID($author['email']))
                ) {
                  $task->setUser($userID);
                  if ($idNote = $task->comment($idTask, [
                    'title' => '',
                    'text' => $note->body
                  ])) {
                    $ctrl->db->update('bbn_tasks_notes', ['id_git' => $note->id], [
                      'id_task' => $idTask,
                      'id_note' => $idNote
                    ]);
                    $ctrl->db->update($notesCfg['table'], [$notesFields['creator'] => $userID], [$notesFields['id'] => $idNote]);
                    $ctrl->db->update($notesCfg['tables']['versions'], [$notesVersionsFields['id_user'] => $userID], [$notesVersionsFields['id_note'] => $idNote]);
                  }
                }
              }
            }
          }
        }
      }
    }
  }

  echo sprintf(_('Did: %d'), $did) . PHP_EOL;
}
