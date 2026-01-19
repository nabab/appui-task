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
        copyRoles: !!this.roles && !!bbn.fn.numProperties(this.roles)
      }
    },
    computed: {
      mainPage(){
        return appui.getRegistered('appui-task');
      },
      fullCategories(){
        if (bbn.cp.isComponent(this.mainPage)
          && (this.mainPage.fullCategories !== undefined)
        ) {
          return bbn.fn.filter(this.mainPage.fullCategories, c => !c.num_children);
        }
        else if (appui.options.tasks?.categories) {
          const res = [];
          const getItem = (cat, group) => {
            let items = cat.items || false;
            delete cat.items;
            cat.value = cat.id;
            cat.group = group || (items?.length ? cat.text : '');
            res.push(cat);
            if ( items ){
            bbn.fn.each(items, (c, i) => {
                getItem(c, cat.text);
              });
            }
          };
          bbn.fn.each(
            bbn.fn.clone(appui.options.tasks.categories),
            cat => {
              getItem(cat);
            }
          );
          return res;
        }

        return [];
      }
    },
    methods: {
      onSuccess(d){
        if (d.success) {
          appui.success();
          this.$emit('taskcreated', d, this.openAfterCreation);
        }
        else {
          appui.error();
          this.$emit('taskfailed', d);
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
          delete this.source.roles;
        }
      }
    }
  }
})();