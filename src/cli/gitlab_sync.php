<?php
if (defined('BBN_GIT_URL')
  && !empty(BBN_GIT_URL)
  && defined('BBN_GIT_PROJECT_TOKEN')
  && !empty(BBN_GIT_PROJECT_TOKEN)
  && defined('BBN_GIT_PROJECT_ID')
  && !empty(BBN_GIT_PROJECT_ID)
) {
  $gitlab = new \bbn\Api\GitLab(BBN_GIT_PROJECT_TOKEN, BBN_GIT_URL);
  $task = new \bbn\Appui\Task($ctrl->db);
  $notes = new \bbn\Appui\Note($ctrl->db);
  $notesCfg = $notes->getClassCfg();
  $notesFields = $notesCfg['arch']['notes'];
  $notesVersionsFields = $notesCfg['arch']['versions'];
  $usersCfg = $ctrl->inc->user->getClassCfg();
  $usersFields = $usersCfg['arch']['users'];
  $idTypeNote = $ctrl->inc->options->fromCode('tasks', 'types', 'note', 'appui');
  $idCatSupportTask = $ctrl->inc->options->fromCode('support', 'cats', 'task', 'appui');
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

  if ($issues = $gitlab->getIssues(BBN_GIT_PROJECT_ID)) {
    foreach ($issues as $issue) {
      if (!empty($issue->id)) {
        // Get the task ID if it already exists
        $idTask = $ctrl->db->selectOne('bbn_tasks', 'id', ['id_git' => $issue->id, 'active' => 1]);
        if (empty($idTask)) {
          $userID = null;
          // Get the issue author
          $author = $gitlab->getUser($issue->author->id);
          // Check if the git user is an appui user
          if (!empty($author['email'])) {
            $userID = $getUserID($author['email']);
          }
          // Otherwise use the external user's ID
          else if (defined('BBN_EXTERNAL_USER_ID')) {
            $userID = BBN_EXTERNAL_USER_ID;
          }
          if (!empty($userID)) {
            // Set the task's user
            $task->setUser($userID);
            // Set the task's date
            $task->setDate(date('Y-m-d H:i:s', strtotime($issue->created_at)));
            // Create the task
            if ($idTask = $task->insert([
              'title' => $issue->title,
              'type' => $idCatSupportTask,
              'state' => $ctrl->inc->options->fromCode($issue->state, 'states', 'task', 'appui'),
              'cfg' => \json_encode(['widgets' => ['notes' => 1]])
            ])) {
              $ctrl->db->update('bbn_tasks', ['id_git' => $issue->id], ['id' => $idTask]);
              $did++;
            }
          }
        }
        // Check if the issue has notes
        if (!empty($idTask)
          && (!empty($issue->user_notes_count))
        ) {
          // Get the issue's notes
          $issueNotes = $gitlab->getIssueNotes(BBN_GIT_PROJECT_ID, $issue->iid);
          if (!empty($issueNotes)) {
            foreach ($issueNotes as $note) {
              // Check if the note already exists on the task's notes
              if ($idNote = $ctrl->db->selectOne('bbn_tasks_notes', 'id_note', [
                'id_git' => $note->id,
                'id_task' => $idTask,
                'active' => 1
                ])) {
                  // Get the note's content
                $n = $notes->get($idNote);
                // Compare the note's content with the issue's note one
                if ($n[$notesVersionsFields['content']] !== $note->body) {
                  // Update the note's content
                  $notes->update($idNote, '', $note->body);
                }
              }
              else {
                $userID = null;
                // Get the issue's note author
                $author = $gitlab->getUser($note->author->id);
                // Check if the git user is an appui user
                if (!empty($author['email'])) {
                  $userID = $getUserID($author['email']);
                }
                // Otherwise use the external user's ID
                else if (defined('BBN_EXTERNAL_USER_ID')) {
                  $userID = BBN_EXTERNAL_USER_ID;
                }
                if (!empty($userID)) {
                  // Set the task's user
                  $task->setUser($userID);
                  // Set the task's date
                  $task->setDate(date('Y-m-d H:i:s', strtotime(!empty($note->updated_at) ? $note->updated_at : $note->created_at)));
                  // Add the note to the task
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

  if ($tasks = $ctrl->db->selectAll([
      'table' => 'bbn_tasks',
      'fields' => [],
      'where' => [
        'conditions' => [[
          'logic' => 'OR',
            'conditions' => [[
            'field' => 'type',
            'value' => $idCatSupportTask
            ], ...array_map(function($o){
              return [
                'field' => 'type',
                'value' => $o
              ];
            }, $ctrl->inc->options->items($idCatSupportTask))]
        ], [
          'field' => 'state',
          'operator' => '!=',
          'value' => $ctrl->inc->options->fromCode('closed', 'states', 'task', 'appui')
        ], [
          'field' => 'active',
          'value' => 1
        ]]
      ]
    ])
  ) {
    foreach ($tasks as $t) {
      if (empty($t->id_git)) {
        if (($idGit = $gitlab->createIssue(BBN_GIT_PROJECT_ID, $t->title, $t->creation_date))
          && $task->setGit($t->id, $idGit)
        ) {
          $did++;
        }
      }
      else {

      }
      if (!empty($idGit)) {

      }
    }
  }
  echo sprintf(_('Did: %d'), $did) . PHP_EOL;
}
