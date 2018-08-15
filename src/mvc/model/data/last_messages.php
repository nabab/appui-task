<?php
/**
 * Created by PhpStorm.
 * User: BBN
 * Date: 04/06/2017
 * Time: 17:33
 */
if ( !empty($model->data['id_task']) ){
  $all = [];
  $topics = $model->db->get_rows("
    SELECT bbn_notes.*, first_version.creation, last_version.title, last_version.content,
      last_version.creation AS last_edit, COUNT(DISTINCT replies.id) AS num_replies,
      IFNULL(MAX(replies_versions.creation), last_version.creation) AS last_reply,
      GROUP_CONCAT(DISTINCT LOWER(HEX(versions.id_user)) SEPARATOR ',') AS users
    FROM bbn_notes
      JOIN bbn_notes_versions AS versions
        ON versions.id_note = bbn_notes.id
      JOIN bbn_notes_versions AS last_version
        ON last_version.id_note = bbn_notes.id
      LEFT JOIN bbn_notes_versions AS test_version
        ON test_version.id_note = bbn_notes.id
        AND last_version.version < test_version.version
      JOIN bbn_notes_versions AS first_version
        ON first_version.id_note = bbn_notes.id
        AND first_version.version = 1
      LEFT JOIN bbn_notes AS replies
        ON replies.id_alias = bbn_notes.id
      LEFT JOIN bbn_notes_versions AS replies_versions
        ON replies_versions.id_note = replies.id
      JOIN bbn_tasks_notes
        ON bbn_tasks_notes.id_note = bbn_notes.id
      JOIN bbn_tasks
        ON bbn_tasks.id = bbn_tasks_notes.id_task
        AND bbn_tasks.active = 1
    WHERE bbn_tasks.id = ?
      AND bbn_notes.active = 1
      AND bbn_notes.id_parent IS NULL
      AND test_version.version IS NULL
      AND bbn_notes.id_alias IS NULL
    GROUP BY bbn_notes.id
    ORDER BY last_reply DESC, last_edit DESC
    LIMIT 0, 20",
    hex2bin($model->data['id_task'])
  );
  if ( !empty($topics) ){
    foreach ( $topics as $top ){
      $all[strtotime($top['last_edit'])] = $top;
      if ( !empty($top['num_replies']) ){
        $replies = $model->db->get_rows("
          SELECT bbn_notes.*, first_version.creation, last_version.title, last_version.content,
            last_version.creation AS last_edit, COUNT(DISTINCT replies.id) AS num_replies,
            IFNULL(MAX(replies_versions.creation), last_version.creation) AS last_reply,
            GROUP_CONCAT(DISTINCT LOWER(HEX(versions.id_user)) SEPARATOR ',') AS users
          FROM bbn_notes
            JOIN bbn_notes_versions AS versions
              ON versions.id_note = bbn_notes.id
            JOIN bbn_notes_versions AS last_version
              ON last_version.id_note = bbn_notes.id
            LEFT JOIN bbn_notes_versions AS test_version
              ON test_version.id_note = bbn_notes.id
              AND last_version.version < test_version.version
            JOIN bbn_notes_versions AS first_version
              ON first_version.id_note = bbn_notes.id
              AND first_version.version = 1
            LEFT JOIN bbn_notes AS replies
              ON replies.id_alias = bbn_notes.id
            LEFT JOIN bbn_notes_versions AS replies_versions
              ON replies_versions.id_note = replies.id
          WHERE bbn_notes.id_alias = ?
            AND bbn_notes.active = 1
            AND test_version.version IS NULL
          GROUP BY bbn_notes.id
          ORDER BY last_edit DESC
          LIMIT 0, 20",
          hex2bin($top['id'])
        );
        if ( !empty($replies) ){
          foreach ( $replies as $rep ){
            $all[strtotime($rep['last_edit'])] = $rep;
          }
        }
      }
    }
  }
  if ( !empty($all) ){
    krsort($all, SORT_NUMERIC);
    $all = array_slice(array_values($all), 0, 20);
  }
  return $all;
}
