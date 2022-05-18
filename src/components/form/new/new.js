(() => {
  return {
    props: ['source'],
    data(){
      return {
        root: appui.plugins['appui-task'] + '/',
        cp: this.closest('bbn-container').getComponent()
      }
    },
    computed: {
      opener(){
        let floater = this.closest('bbn-floater');
        return bbn.fn.isVue(floater) ? floater.opener : false;
      },
      fullCategories(){
        if (bbn.fn.isVue(this.opener)) {
          if (bbn.fn.isVue(this.opener.tasks)
            && (this.opener.tasks.fullCategories !== undefined)
          ) {
            return this.opener.tasks.fullCategories;
          }
        }
        return [];
      }
    },
    methods: {
      onSuccess(d){
        if (bbn.fn.isVue(this.opener)) {
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
            let widget = this.opener.findByKey('tasks', 'bbn-widget');
            if (bbn.fn.isVue(widget)) {
              widget.reload();
            }
          }
        }
      }
    }
  }
})();