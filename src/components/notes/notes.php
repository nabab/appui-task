<appui-note-forum ref="forum"
                  :source="root + 'data/messages'"
                  :data="{id_task: source.id}"
                  :pageable="true"
                  :toolbar="[{
                    label: '<?= _('Add a note') ?>',
                    action: insert,
                    icon: 'nf nf-fa-pencil',
                    disabled: isClosed
                  }]"
                  :edit-enabled="!isClosed"
                  :reply-enabled="!isClosed"
                  :remove-enabled="!isClosed"
                  @edit="edit"
                  @reply="reply"
                  @remove="removeItem"
                  :image-dom="imageDom"
                  :download-url="root + 'download/media/'"
                  :filterable="true"
                  :search="true"/>