<?php
/*
 * Describe what it does to show you're not that dumb!
 *
 **/

/** @var $this \bbn\mvc\controller */
if ( isset($this->post['url']) ){
  $this->obj->res = $this->get_model(['url' => $this->post['url']]);
}