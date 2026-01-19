(() => {
  return {
    mixins: [bbn.cp.mixins.basic],
    data(){
      return {
        webmailReader: null
      }
    },
    computed: {
      isDisabled(){
        return !this.webmailReader?.currentSelectedSource
          || !!this.webmailReader.currentSelectedSource.is_draft;
      },
      contextSrc(){
        return [{
          text: bbn._('Create a new task'),
          action: this.newTask,
          icon: 'nf nf-fa-plus',
          disabled: !!this.webmailReader?.currentSelectedSource?.id_task
        }, {
          text: bbn._('Create a new subtask'),
          action: this.newSubtask,
          icon: 'nf nf-md-subdirectory_arrow_right',
          disabled: !!this.webmailReader?.currentSelectedSource?.id_task
        }, {
          text: bbn._("Create a new task's note"),
          action: this.newNote,
          icon: 'nf nf-md-note_plus'
        }];
      }
    },
    methods: {
      newTask(){
        if (this.webmailReader.currentSelectedSource) {
          const src = this.webmailReader.currentSelectedSource;
          this.getPopup({
            label: bbn._('New task'),
            width: 500,
            component: 'appui-task-form-new',
            source: {
              title: src.subject || '',
              type: '',
              private: 1
            },
            componentEvents: {
              taskcreated: (d, openAfterCreation) => {
                if (d.success && !!d.id) {
                  this.post(appui.plugins['appui-email'] + '/webmail/actions/email/task', {
                    id_email: src.id,
                    id_task: d.id
                  }, r => {
                    if (r.success) {
                      src.id_task = d.id;
                      appui.success();
                      if (openAfterCreation) {
                        bbn.fn.link(appui.plugins['appui-task'] + '/page/task/' + d.id);
                      }
                    }
                    else {
                      appui.error(r.error || bbn._('An error occurred'));
                    }
                  });
                }
                else {
                  appui.error(d.error || bbn._('An error occurred'));
                }
              }
            }
          });
        }
      },
      newSubtask(){
      },
      newNote(){}
    },
    beforeCreate() {
      this.webmailReader = this.closest('appui-email-webmail-reader');
    }
  }
})();