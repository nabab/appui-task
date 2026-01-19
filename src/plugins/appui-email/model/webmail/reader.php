<?php

use bbn\Mvc;

return [
  'toolbar' => [
    'content' => Mvc::getInstance()->subpluginView('webmail/reader/toolbar', 'html', [], 'appui-task', 'appui-email'),
    'script' => Mvc::getInstance()->subpluginView('webmail/reader/toolbar', 'js', [], 'appui-task', 'appui-email'),
  ]
];


