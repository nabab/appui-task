<?php
if (
  !empty($model->data['id_task']) &&
  $model->hasPlugin('appui-billing') &&
  ($invoice = $model->getModel($model->pluginUrl('appui-billing') . '/actions/bill', $model->data)) &&
  !empty($invoice['success']) &&
  !empty($invoice['data']) &&
  $model->db->insert('bbn_tasks_invoices', [
    'id_task' => $model->data['id_task'],
    'id_invoice' => $invoice['data']['id']
  ])
){
  return [
    'success' => true,
    'data' => $invoice['data'],
  ];
}
return ['success' => false];
