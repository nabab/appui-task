(() => {
  return {
    props: {
      source: {
        type: Object
      },
      roles: {
        type: [Boolean, Object],
        default: false
      }
    },
    data(){
      return {
        root: appui.plugins['appui-task'] + '/',
        openAfterCreation: true,
        copyRoles: !!this.roles && bbn.fn.numProperties(this.roles)
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
            this.opener.$emit('taskcreated', d, this.openAfterCreation);
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
      },
      setRoles(){
        if (bbn.fn.isObject(this.roles)
          && bbn.fn.numProperties(this.roles)
        ) {
          this.$set(this.source, 'roles', this.roles);
        }
      }
    },
    created(){
      this.setRoles();
    },
    watch: {
      copyRoles(newVal){
        if (newVal) {
          this.setRoles();
        }
        else {
          this.$delete(this.source, 'roles');
        }
      }
    }
  }
})();