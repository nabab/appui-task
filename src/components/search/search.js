(() => {
  return {
    props: {
      source: {
        type: Object
      },
      idParent: {
        type: String,
        required: true
      }
    },
    data(){
      return {
        mainPage: null,
        currentSearch: ''
      }
    },
    computed: {
      filters(){
        let obj = {
          logic: 'AND',
          conditions: [{
            field: 'id_parent',
            operator: 'isnull'
          }, {
            field: 'state',
            operator: '!=',
            value: this.mainPage.states.closed
          }]
        };
        if (this.currentSearch.length) {
          obj.conditions.push({
            field: 'title',
            operator: 'contains',
            value: this.currentSearch
          });
        }
        return obj;
      }
    },
    methods: {
      clearSearch(){
        if (this.currentSearch.length) {
          this.currentSearch = '';
        }
      }
    },
    created(){
      this.$set(this, 'mainPage', this.closest('appui-task'));
    },
    components: {
      item: {
        template: `
          <div class="bbn-flex-width">
            <appui-task-item :source="source"
                             :editable="false"
                             class="bbn-flex-fill bbn-right-space"/>
            <bbn-button :icon="isAdded ? 'nf nf-fa-check bbn-green bbn-lg' : 'nf nf-fa-plus bbn-lg'"
                        @click="add"/>

          </div>
        `,
        props: {
          source: {
            type: Object,
            required: true
          },
          parent: {
            type: Object
          },
          idParent: {
            type: String,
            required: true
          }
        },
        data(){
          return {
            isAdded: false
          }
        },
        methods: {
          add(){
            if (!this.isAdded
              && !!this.idParent
              && !!this.source.id
              && !this.source.id_parent
            ) {
              this.post(appui.plugins['appui-task'] + '/actions/task/subtask', {
                id: this.source.id,
                idParent: this.idParent
              }, d => {
                if (d.success) {
                  if (!!this.parent
                    && (this.parent.children !== undefined)
                    && (d.children !== undefined)
                  ) {
                    this.parent.children.splice(0, this.parent.children.length, ...d.children);
                    this.source.num_children = d.children.length;
                    let task = appui.getRegistered('appui-task-' + this.idParent);
                    if (task) {
                      let widget = task.getRef('subtasksWidget');
                      if (widget) {
                        this.$nextTick(() => {
                          widget.reload();
                        })
                      }
                    }
                  }
                  this.isAdded = true;
                  appui.success();
                }
              });
            }
          }
        }
      }
    }
  }
})();