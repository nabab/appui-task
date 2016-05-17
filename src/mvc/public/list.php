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
      'is_dev' => $this->inc->user->is_admin(),
      'lng' => [
        'developpment' => _("Taches de developpement - "),
        'auter' =>_("Auteur"),
        'inconnu' => _("Inconnu!"),
        'cmt' => _("#Cmt"),
        'active' => _("Activité"),
        'duree' => _("Durée"),
        'titre' => _("Titre"),
        'priority' => _("Priorité"),
        'objectif' => _("Objectif"),
        'statut' => _("Statut"),
        'ouvert' => _("Ouvert"),
        'en_cours'=> _("En cours"),
        'resolu'  => _("Résolu"),
        'en_attente'=> _("En attente"),
        'actions' => _("Actions"),
        'desabonner' => _("Se désabonner"),
        'abboner' => _("S'abonner"),
        'date' => _("Date"),
        'texte' => _("Texte"),
        'modifier' => _("Modifier"),
        'sauvegarder' => _("Sauvegarder"),
        'annuler' => _("Annuler"),
        'supprimer' => _("Supprimer"),
        'priority_re' => _("Réhausser la priorité"),
        'priority_ra' => _("Rabaisser la priorité"),
        'sauver' => _("Sauver"),
        'inscrit' => _("Vous &ecirc;tes désormais bien inscrit aux notifications concernant cette t&acirc;che"),
        'succes' => _("Succès"),
        'desinscrit' => _("Vous &ecirc;tes désormais désinscrit des notifications concernant cette t&acirc;che"),
        'problem' => _("Il y a eu un problème..."),
        'bug' => _("Nouveau bug ou problème"),
        'priority_max' => _("La priorité est déjà maximale (1)"),
        'priority_r' => _("Priorité réhaussée"),
        'priority_min' => _("La priorité est déjà minimale (5)"),
        'priority_abb' => _("Priorité abaissée")
      ]
	  ])
    ->get_view();
}
else{
  $this->data = $this->post;
  $this->obj = $this->get_model();
}