<div class="bbn-overlay">
  <bbn-tracks :source="root + 'data/tracks'"
              @dataloaded="goToTrack"
              ref="tracks"
              uid="id"
              :editable="true"
              :editor="$options.components.editor"
              @edit="onEdit"
              @editSuccess="onEditSuccess"
              @editFailure="onEditFailure"/>
</div>