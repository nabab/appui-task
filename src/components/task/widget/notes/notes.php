<div style="height: 500px">
  <appui-note-forum ref="forum"
                    :source="root + 'data/messages'"
                    :data="{id_task: source.id}"
                    :pageable="true"
                    :toolbar="[{
                      text: '<?=_('Add a note')?>',
                      action: insert,
                      icon: 'nf nf-fa-pencil'
                    }]"
                    :edit="edit"
                    :reply="reply"
                    :remove="remove"
                    :image-dom="root + 'image/'"
                    :download-url="root + 'download/media/'"/>
</div>