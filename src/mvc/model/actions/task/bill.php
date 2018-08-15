<?php
if (
  !empty($model->data['id_task']) &&
  $model->has_plugin('appui-billing') &&
  ($invoice = $model->get_model($model->plugin_url('appui-billing') . '/actions/bill', $model->data)) &&
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
