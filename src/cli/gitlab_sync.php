<?php
$projectToken = 'glpat-2QAj5FxGNBSjqbL-Pmc1';
$userToken = 'glpat-cyzbhxQLDHsrbJqFBMyr';
$token = 'glpat-PC7yyU-qS1MvMMiaGXDy';
$host = 'https://git.bbn.so';
$projectID = 125;
$gitlab = new \bbn\Api\GitLab($projectToken, $host);
$task = new \bbn\Appui\Task($ctrl->db);
$notes = new \bbn\Appui\Note($ctrl->db);
$notesCfg = $notes->getClassCfg();
$notesFields = $notesCfg['arch']['notes'];
$notesVersionsFields = $notesCfg['arch']['versions'];
$usersCfg = $ctrl->inc->user->getClassCfg();
$usersFields = $usersCfg['arch']['users'];
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
    die(var_dump($issue));
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
        if ($issueNotes = $gitlab->getIssueNotes($projectID, $issue->iid)) {
          foreach ($issuNotes as $note) {
            if ($idNote = $ctrl->db->selectOne('bbn_tasks_notes', 'id_note', [
              'id_git' => $note->id,
              'id_task' => $idTask
            ])) {

            }
            else if ($idNote = $notes->insert('', $note->body, !empty($note->confidential), false, null, null, '', 'en')) {
              $author = $gitlab->getUser($note->author->id);
              if (!empty($author['email'])
                && ($userID = $getUserID($author['email']))
              ) {
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

echo sprintf(_('Did: %d'), $did) . PHP_EOL;