<?php

use bbn\X;
use bbn\Str;
/** @var bbn\Mvc\Controller $ctrl */

if (!empty($ctrl->post['limit'])) {
  $ctrl->action();
}
else {
  $ctrl->combo(_("Budgets"), true);
}
