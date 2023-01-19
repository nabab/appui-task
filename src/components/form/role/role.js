(() => {
  return {
    props: {
      source: {
        type: Object,
        required: true
      },
      role: {
        type: String,
        required: true
      },
      manage: {
        type: Boolean,
        default: false
      }
    },
    data(){
      let groupsUsers = [];
      let activeUsers = appui.app.getActiveUsers();
      if (this.source.roles[this.role] === undefined) {
        this.$set(this.source.roles, this.role, []);
      }
      if (appui.app.groups && activeUsers) {
        appui.app.groups.forEach(group => {
          let users = activeUsers.filter(u => {
            if ((u.id_group === group.id)
              && (u.value !== appui.app.user.id)
              && (u.value !== this.source.id_user)
            ) {
              let hasRole = false;
              bbn.fn.iterate(this.source.roles, r => {
                if (r.includes(u.value)) {
                  hasRole = true;
                  return false;
                }
              });
              return !hasRole;
            }
            return false;
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
        root: appui.plugins['appui-task'] + '/',
        originalValue: bbn.fn.extend(true, [], this.source.roles[this.role])
      }
    },
    computed: {
      currentRoleList(){
        return this.source.roles[this.role];
      }
    },
    methods: {
      onSubmit(ev){
        if (!!this.source.id) {
          let obj = {
                id_task: this.source.id,
                role: this.role,
              },
              canPost = false;
          if (this.manage) {
            obj.toAdd = bbn.fn.filter(this.currentRoleList, u => !this.originalValue.includes(u));
            obj.toRemove = bbn.fn.filter(this.originalValue, u => !this.currentRoleList.includes(u));
            canPost = !!obj.toAdd.length || !!obj.toRemove.length;
          }
          else if (this.currentRoleList.length) {
            obj.id_user = this.currentRoleList;
            canPost = true;
          }
          if (canPost) {
            this.post(this.root + 'actions/role/' + (this.manage ? 'update' : 'insert'), obj, d => {
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
  }
})();