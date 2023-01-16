(() => {
  return {
    props: {
      source: {
        type: Object,
        required: true
      },
      idTask: {
        type: String,
        required: true
      },
      role: {
        type: String,
        required: true
      }
    },
    data(){
      let groupsUsers = [];
      let activeUsers = appui.app.getActiveUsers();
      if (appui.app.groups && activeUsers) {
        appui.app.groups.forEach(group => {
          let users = activeUsers.filter(u => {
              return (u.id_group === group.id)
                && (u.value !== appui.app.user.id)
                && !this.source[this.role].includes(u.value);
          });
          users = users.map(u => {
            let user = bbn.fn.extend(true, {
              icon: 'nf nf-fa-user',
              id: u.value
            }, u);
            delete user.value;
            return user;
          });
          groupsUsers.push({
            id: group.id,
            text: group.nom || group.group,
            items: bbn.fn.order(users, 'text'),
            num: users.length,
            icon: 'nf nf-fa-users'
          });
        });
      }
      return {
        users: bbn.fn.order(groupsUsers, 'text'),
        root: appui.plugins['appui-task'] + '/'
      }
    },
    methods: {
      onSubmit(ev){
        if (this.source[this.role].length && !!this.idTask) {
          this.post(this.root + 'actions/role/insert', {
            id_task: this.idTask,
            role: this.role,
            id_user: this.source[this.role]
          }, d => {
            if (d.success) {
              this.getRef('form').closePopup(true);
              appui.success();
            }
            else {
              appui.error();
            }
          });
        }
      }
    }
  }
})();