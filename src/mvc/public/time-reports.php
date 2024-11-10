<?php

use bbn\X;
use bbn\Str;
/** @var bbn\Mvc\Controller $ctrl */
if (!empty($ctrl->post['limit'])) {
  $ctrl->action();
}
else {
  $ctrl->setIcon("nf nf-md-clipboard_text_clock_outline")
    ->combo(_("Time reports"));
}
