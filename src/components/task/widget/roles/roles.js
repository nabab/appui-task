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
            component: 'appui-task-form-role',
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
            if (idx > -1) {
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
    }
  }
})();