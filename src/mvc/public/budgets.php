<?php

use bbn\X;
use bbn\Str;
/** @var $ctrl \bbn\Mvc\Controller */

if (!empty($ctrl->post['limit'])) {
  $ctrl->action();
}
else {
  $ctrl->combo(_("Budgets"), true);
}
