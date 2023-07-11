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
    data(){
      return {
        isManagerOpen: !!this.source.roles || !!this.source.roles.managers,
        isWorkerOpen: true,
        isViewerOpen: true
      }
    },
    computed: {
      managers(){
        if (!!this.source.roles && !!this.source.roles.managers) {
          return bbn.fn.order(bbn.fn.map(this.source.roles.managers.slice(), u => {
            return {
              idUser: u,
              userName: this.userName(u)
            }
          }), 'userName');
        }
        return [];
      },
      workers(){
        if (!!this.source.roles && !!this.source.roles.workers) {
          return bbn.fn.order(bbn.fn.map(this.source.roles.workers.slice(), u => {
            return {
              idUser: u,
              userName: this.userName(u)
            }
          }), 'userName');
        }
        return [];
      },
      viewers(){
        if (!!this.source.roles && !!this.source.roles.viewers) {
          return bbn.fn.order(bbn.fn.map(this.source.roles.viewers.slice(), u => {
            return {
              idUser: u,
              userName: this.userName(u)
            }
          }), 'userName');
        }
        return [];
      }
    },
    methods: {
      addRole(role){
        if (this.task.canChange && !!role) {
          this.getPopup({
            component: 'appui-task-form-role',
            componentOptions: {
              source: this.source,
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
                  if (d.roles !== undefined) {
                    let comps = this.task.mainPage.findAllByKey(this.source.id, 'appui-task-item');
                    if (comps.length) {
                      bbn.fn.each(comps, c => c.$set(c.source, 'roles', d.roles));
                      }
                    let t = appui.getRegistered('appui-task-' + this.source.id, true);
                    if (t) {
                      this.$set(t.source, 'roles', d.roles);
                    }
                  }
                  this.task.askSetSubtasksRoles();
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
