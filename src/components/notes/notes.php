<appui-note-forum ref="forum"
                  :source="root + 'data/messages'"
                  :data="{id_task: source.id}"
                  :pageable="true"
                  :toolbar="[{
                    text: '<?=_('Add a note')?>',
                    action: insert,
                    icon: 'nf nf-fa-pencil',
                    disabled: !canChange
                  }]"
                  :edit="canChange ? edit : false"
                  :reply="canChange ? reply : false"
                  :remove="canChange ? remove : false"
                  :image-dom="root + 'image/'"
                  :download-url="root + 'download/media/'"/>