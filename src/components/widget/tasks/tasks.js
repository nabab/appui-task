(() => {
  return {
    data(){
      return {
        root: appui.plugins['appui-task'] + '/'
      }
    },
    computed: {
      main(){
        let main = this.closest('appui-task-tab-main');
        return bbn.fn.isVue(main) ? main : false;
      },
      state(){
        if (this.main && bbn.fn.isVue(this.main.tasks)) {
          return bbn.fn.getField(this.main.tasks.source.options.states, 'text', 'value', this.source.state);
        }
        return '';
      },
      creationDate(){
        return dayjs(this.source.creationDate).format('DD/MM/YYYY');
      },
      role(){
        if (this.main
          && bbn.fn.isVue(this.main.tasks)
          && bbn.fn.numProperties(this.source.roles)
        ) {
          let code = false
          bbn.fn.iterate(this.source.roles, (ids, role) => {
            if (ids.includes(appui.app.user.id)) {
              code = role;
            }
          });
          if (code) {
            return bbn.fn.getField(this.main.tasks.source.options.roles, 'text', 'code', code) || '';
          }
        }
        return '';
      }
    },
    methods: {
      openTask(){
        if (this.source.id) {
          bbn.fn.link(this.root + 'page/task/' + this.source.id);
        }
      }
    }
  };
})();