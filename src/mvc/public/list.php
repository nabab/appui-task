<?php
/* @var $this \bbn\mvc\controller */

if ( empty($this->post) ){
  echo $this
    ->set_title('Tâches de développement')
    ->add_js([
      'root' => $this->say_dir(),
      'editable' => $this->inc->user->is_admin() ? [
        'confirmation' => "Etes-vous sûr de vouloir supprimer cette tâche?",
        'mode' => "inline"
      ] : false,
      'is_dev' => $this->inc->user->is_admin()
	  ])
    ->get_view();
}
else{
  $this->data = $this->post;
  $this->obj = $this->get_model();
}