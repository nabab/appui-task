(() => {
  return {
    computed: {
      stateCode(){
        if (this.optionsStates) {
          return bbn.fn.getField(this.optionsStates, 'code', 'value', this.source.state);
        }
        return false;
      },
      state(){
        if (this.optionsStates) {
          return bbn.fn.getField(this.optionsStates, 'text', 'value', this.source.state);
        }
        return '';
      },
      creationDate(){
        return dayjs(this.source.creationDate).format('DD/MM/YYYY HH:mm');
      },
      roleCode(){
        let code = false
        if (bbn.fn.numProperties(this.source.roles)) {
          bbn.fn.iterate(this.source.roles, (ids, role) => {
            if (ids.includes(appui.app.user.id)) {
              code = role;
            }
          });
          return code;
        }
      },
      role(){
        if (this.roleCode) {
          return bbn.fn.getField(this.optionsRoles, 'text', 'code', this.roleCode) || '';
        }
        return '';
      },
      author(){
        return this.mainPage.userName(this.source.id_user);
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