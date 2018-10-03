<appui-notes-forum ref="forum"
                   :source="tasks.source.root + 'data/messages'"
                   :data="{id_task: source.id}"
                   :pageable="true"
                   :toolbar="[{
                     text: '<?=_('Add a note')?>',
                     command: insert,
                     icon: 'fas fa-pencil-alt'
                   }]"
                   :edit="edit"
                   :reply="reply"
                   :remove="remove"
                   image-dom="pm/image/"
                   download-url="pm/download/media/"
></appui-notes-forum>