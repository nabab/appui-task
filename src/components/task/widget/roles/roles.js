/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 26/04/2018
 * Time: 10:50
 */
(() => {
  return {
    props: {
      source: {
        type: Object
      }
    },
    computed: {
      managers(){
        return bbn.fn.order(bbn.fn.map(this.source.roles.managers.slice(), u => {
          return {
            idUser: u,
            userName: this.userName(u)
          }
        }), 'userName');
      },
      workers(){
        return bbn.fn.order(bbn.fn.map(this.source.roles.workers.slice(), u => {
          return {
            idUser: u,
            userName: this.userName(u)
          }
        }), 'userName');
      },
      viewers(){
        return bbn.fn.order(bbn.fn.map(this.source.roles.viewers.slice(), u => {
          return {
            idUser: u,
            userName: this.userName(u)
          }
        }), 'userName');
      }
    },
    methods: {
      addRole(role){
        if (this.task.canChange && !!role) {
          this.getPopup({
            component: this.$options.components.userPicker,
            componentOptions: {
              source: this.source.roles,
              idTask: this.source.id,
              role: role
            },
            title: bbn._('Select user(s)'),
            width: 400,
            height: 600
          });
        }
      },
      removeRole(user, role){
        if (this.task.canChange && !!role) {
          this.confirm(bbn._('Are you sure you want to remove this user?'), () => {
            const idx = this.source.roles[role].indexOf(user);
            if ( (idx > -1) ){
              this.post(this.root + 'actions/role/delete', {
                id_task: this.source.id,
                id_user: user
              }, d => {
                if (d.success) {
                  this.source.roles[role].splice(idx, 1);
                  appui.success();
                }
                else {
                  appui.error();
                }
              });
            }
          });
        }
      }
    },
    components: {
      userPicker: {
        template: `
<bbn-form :scrollable="false"
          class="bbn-overlay"
          :source="source"
          @submit.prevent="onSubmit"
          ref="form">
  <div class="bbn-overlay bbn-hpadded bbn-vspadded">
    <appui-usergroup-picker :multi="true"
                            class="bbn-h-100"
                            v-model="source[role]"
                            :as-array="true"
                            :source="users"/>
  </div>
</bbn-form>`,
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
          if (appui.app.groups && appui.app.users) {
            appui.app.groups.forEach(group => {
              let users = appui.app.users.filter(u => {
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
    }
  }
})();