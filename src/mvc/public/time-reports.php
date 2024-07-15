<?php

use bbn\X;
use bbn\Str;
/** @var $ctrl \bbn\Mvc\Controller */
if (!empty($ctrl->post['limit'])) {
  $ctrl->action();
}
else {
  $ctrl->setIcon("nf nf-md-clipboard_text_clock_outline")
    ->combo(_("Time reports"));
}
