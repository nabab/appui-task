<?php

use bbn\Mvc;

return [
  'toolbar' => [
    'content' => Mvc::getInstance()->subpluginView('webmail/toolbar', 'html', [], 'appui-task', 'appui-email'),
    'script' => Mvc::getInstance()->subpluginView('webmail/toolbar', 'js', [], 'appui-task', 'appui-email'),
  ]
];


