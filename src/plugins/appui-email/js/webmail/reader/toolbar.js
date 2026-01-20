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
          action: () => this.newTask(),
          icon: 'nf nf-fa-plus',
          disabled: !!this.webmailReader?.currentSelectedSource?.id_task
        }, {
          text: bbn._('Create a new subtask'),
          action: () => this.newTask(true),
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
      newTask(isSubtask = false){
        if (this.webmailReader.currentSelectedSource) {
          const src = this.webmailReader.currentSelectedSource;
          this.getPopup({
            label: bbn._('New task'),
            width: 500,
            component: 'appui-task-form-new',
            componentOptions: {
              source: {
                title: src.subject || '',
                type: '',
                private: 1,
                id_parent: isSubtask ? '' : null
              },
              subtask: isSubtask
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
      newNote(){
        if (this.webmailReader.currentSelectedSource) {
          const src = this.webmailReader.currentSelectedSource;
          this.getPopup({
            label: bbn._("Save as message of a task"),
            width: 700,
            component: this.$options.components.note,
            source: {
              title: src.subject || '',
              text: src.excerpt || '',
              files: [],
              links: [],
              locked: 1,
              id_task: '',
              ref: bbn.fn.microtimestamp()
            },
            componentEvents: {
              success: d => {
                if (d.success && d.data?.id_task && d.data?.id_note) {
                  this.post(appui.plugins['appui-email'] + '/webmail/actions/email/tasknote', {
                    id_email: src.id,
                    id_task: d.data.id_task,
                    id_note: d.data.id_note
                  }, r => {
                    if (r.success) {
                      appui.success();
                    }
                  });
                }
                else {
                  appui.error(d.error || bbn._('An error occurred'));
                }
              },
              failure: d => {
                appui.error(d.error || bbn._('An error occurred'));
              }
            }
          });
        }
      }
    },
    beforeCreate() {
      this.webmailReader = this.closest('appui-email-webmail-reader');
    },
    components: {
      note: {
        template: `
          <bbn-form :action="root + 'actions/messages/insert'"
                    :source="source"
                    @success="onSuccess"
                    @failure="onFailure">
            <div class="bbn-padding bbn-grid-fields">
              <label class="bbn-label">` + bbn._('Task') + `</label>
              <bbn-autocomplete :source="root + 'data/list'"
                                bbn-model="source.id_task"
                                :required="true"
                                placeholder="` + bbn._('Search and select a task...') + `"
                                class="bbn-w-100"
                                source-text="title"
                                source-value="id"/>
            </div>
          </bbn-form>
        `,
        props: {
          source: {
            type: Object
          }
        },
        data(){
          return {
            root: appui.plugins['appui-task'] + '/'
          }
        },
        methods: {
          onSuccess(d){
            this.$emit('success', d);
          },
          onFailure(d){
            this.$emit('failure', d);
          }
        }
      }
    }
  }
})();