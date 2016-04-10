<?php
/*
 * Describe what it does or you're a pussy
 *
 **/

/** @var $this \bbn\mvc\model*/
if ( isset($this->data['url']) && \bbn\str::is_url($this->data['url']) ){
  $linkPreview = new \LinkPreview\LinkPreview($this->data['url']);
  $parsed = $linkPreview->getParsed();
  foreach ($parsed as $parserName => $link) {
    if ( $parserName === 'general' ){
      return [
        'url' => $link->getUrl(),
        'realurl' => $link->getRealUrl(),
        'title' => $link->getTitle(),
        'desc' => $link->getDescription(),
        'img' => $link->getImage(),
        'pictures' => $link->getPictures()
      ];
    }
  }
}