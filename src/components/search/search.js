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
      this.$set(this, 'mainPage', appui.getRegistered('appui-task'));
    },
    components: {
      item: {
        template: `
          <div class="bbn-flex-width">
            <appui-task-item :source="source"
                             :editable="false"
                             class="bbn-flex-fill bbn-right-space"/>
            <bbn-button v-if="isAdded"
                        icon="nf nf-fa-minus bbn-lg"
                        @click="remove"
                        class="bbn-no-border bbn-alt-background bbn-red"/>
            <bbn-button v-else
                        icon="nf nf-fa-plus bbn-lg"
                        @click="add"
                        class="bbn-no-border bbn-alt-background bbn-green"/>

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
              this.post(appui.plugins['appui-task'] + '/actions/task/subtask/insert', {
                id: this.source.id,
                idParent: this.idParent
              }, d => {
                if (d.success) {
                  this.updateParent(d.children);
                  this.source.id_parent = this.idParent;
                  this.isAdded = true;
                  appui.success();
                }
              });
            }
          },
          remove(){
            if (this.isAdded
              && !!this.idParent
              && !!this.source.id
              && !!this.source.id_parent
            ) {
              this.post(appui.plugins['appui-task'] + '/actions/task/subtask/remove', {
                id: this.source.id
              }, d => {
                if (d.success) {
                  this.updateParent(d.children);
                  this.source.id_parent = null;
                  this.isAdded = false;
                  appui.success();
                }
              });
            }
          },
          updateParent(children){
            if (!!this.parent
              && (this.parent.children !== undefined)
              && (children !== undefined)
            ) {
              this.parent.children.splice(0, this.parent.children.length, ...children);
              this.source.num_children = children.length;
              let task = appui.getRegistered('appui-task-' + this.idParent, true);
              if (task) {
                let widget = task.findByKey('subtasks', 'bbn-widget');
                if (widget) {
                  this.$nextTick(() => {
                    widget.reload();
                  })
                }
              }
            }
          }
        }
      }
    }
  }
})();