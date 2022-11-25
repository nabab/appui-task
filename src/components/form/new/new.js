(() => {
  return {
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
    computed: {
      opener(){
        let floater = this.closest('bbn-floater');
        return bbn.fn.isVue(floater) ? floater.opener : false;
      },
      mainPage(){
        return this.closest('appui-task');
      },
      fullCategories(){
        if (bbn.fn.isVue(this.mainPage)
          && (this.mainPage.fullCategories !== undefined)
        ) {
          return this.mainPage.fullCategories;
        }
        return [];
      }
    },
    methods: {
      onSuccess(d){
        if (d.success) {
          appui.success();
          if (bbn.fn.isVue(this.opener)) {
            this.opener.$emit('taskcreated', d);
            /*
            if (this.opener.taskTitle !== undefined) {
              this.opener.taskTitle = '';
            }
            if (bbn.fn.isFunction(this.opener.openTask)) {
              this.opener.openTask(d.success);
            }
            if ((d.children !== undefined)
              && bbn.fn.isArray(this.opener.source.children)
            ) {
              this.opener.source.children.splice(0, this.opener.source.children.length, ...d.children);
              this.opener.source.num_children = d.children.length;
              this.opener.source.has_children = !!d.children.length;
              let widget = this.opener.findByKey('subtasks', 'bbn-widget');
              if (bbn.fn.isVue(widget)) {
                widget.reload();
              }
            }
            */
          }
        }
        else {
          appui.error();
        }
      }
    }
  }
})();