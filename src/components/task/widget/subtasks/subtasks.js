(() => {
  return {
    data(){
      return {
        root: appui.plugins['appui-task'] + '/'
      }
    },
    computed: {
      state(){
        if (this.optionsStates) {
          return bbn.fn.getField(this.optionsStates, 'text', 'value', this.source.state);
        }
        return '';
      },
      creationDate(){
        return dayjs(this.source.creationDate).format('DD/MM/YYYY');
      },
      role(){
        if (bbn.fn.numProperties(this.source.roles)) {
          let code = false
          bbn.fn.iterate(this.source.roles, (ids, role) => {
            if (ids.includes(appui.app.user.id)) {
              code = role;
            }
          });
          if (code) {
            return bbn.fn.getField(this.optionsRoles, 'text', 'code', code) || '';
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