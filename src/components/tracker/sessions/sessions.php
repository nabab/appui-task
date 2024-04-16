<div class="appui-task-tracker-sessions bbn-overlay">
  <bbn-tracks :source="root + 'data/sessions'"
              @dataloaded="goToTrack"
              ref="tracks"
              uid="id"
              editable="inline"
              :editor="$options.components.toolbarEditor"
              @edit="onEdit"
              @editsuccess="onEditSuccess"
              @editfailure="onEditFailure"
              :map="mapItems"/>
</div>