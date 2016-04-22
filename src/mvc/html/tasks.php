<!-- HTML Document -->
<?php if ( $is_template ){ ?>
<div class="appui-full-height">
	<div class="info-tabstrip" style="height:100%" id="appui_task<?=$id?>"></div>
</div>
<?php } else { ?>
<div class="appui-task-opened-tabstrip appui-full-height">
</div>
<div class="appui-task-opened-desc container-placeholder appui-lg">
  <i class="fa fa-question-circle"> </i>
  <?=_("Opened projects come here")?>
</div>
<?php } ?>