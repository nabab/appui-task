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