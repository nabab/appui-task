<appui-note-forum ref="forum"
                  :source="root + 'data/messages'"
                  :data="{id_task: source.id}"
                  :pageable="true"
                  :toolbar="[{
                    text: '<?=_('Add a note')?>',
                    action: insert,
                    icon: 'nf nf-fa-pencil',
                    disabled: isClosed
                  }]"
                  :edit="!isClosed ? edit : false"
                  :reply="!isClosed ? reply : false"
                  :remove="!isClosed ? remove : false"
                  :image-dom="root + 'image/'"
                  :download-url="root + 'download/media/'"/>