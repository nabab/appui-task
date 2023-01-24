<?php
if (isset($ctrl->post['start'])) {
  $ctrl->action();
}
else {
  $ctrl->combo(_('Logs'), true);
}
