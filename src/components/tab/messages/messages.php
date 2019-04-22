<appui-notes-forum ref="forum"
                   :source="tasks.source.root + 'data/messages'"
                   :data="{id_task: source.id}"
                   :pageable="true"
                   :toolbar="[{
                     text: '<?=_('Add a note')?>',
                     command: insert,
                     icon: 'nf nf-fa-pencil_alt'
                   }]"
                   :edit="edit"
                   :reply="reply"
                   :remove="remove"
                   image-dom="pm/image/"
                   download-url="pm/download/media/"
></appui-notes-forum>
