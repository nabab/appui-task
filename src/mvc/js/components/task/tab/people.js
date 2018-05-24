/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 08/06/2017
 * Time: 19:38
 */
(() => {
  return {
    props: ['source'],
    data(){
      return {
        targetContainer: false
      }
    },
    computed: {
      isMaster(){
        return appui.app.userId === this.source.id_user;
      },
      isClosed(){
        return this.source.state === this.tasks.source.states.closed;
      },
      isManager(){
        if ( this.isMaster ){
          return true;
        }
        return this.source.roles.managers && ($.inArray(appui.app.userId, this.source.roles.managers) > -1);
      },
      canChange(){
        return !this.isClosed && this.isMaster;
      },
      managers(){
        if ( this.source.roles && this.source.roles.managers ){
          return bbn.fn.order($.map(this.source.roles.managers, (v) => {
            let user = bbn.fn.get_row(appui.app.users, 'value', v);
              return user || undefined;
          }), 'text', 'asc');
        }
        return [];
      },
      viewers(){
        if ( this.source.roles && this.source.roles.viewers ){
          return bbn.fn.order($.map(this.source.roles.viewers, (v) => {
            let user = bbn.fn.get_row(appui.app.users, 'value', v);
            return user || undefined;
          }), 'text', 'asc');
        }
        return [];
      },
      workers(){
        if ( this.source.roles && this.source.roles.workers ){
          return bbn.fn.order($.map(this.source.roles.workers, (v) => {
            let user = bbn.fn.get_row(appui.app.users, 'value', v);
            return user || undefined;
          }), 'text', 'asc');
        }
        return [];
      },
      groupsFiltered(){
        return bbn.fn.order($.map(this.tasks.groups, (g) => {
          let a = $.extend(true, {}, g);
          a.items = a.items.filter((u) => {
            let ok = true;
            $.each(this.source.roles, (j, r) => {
              if ( $.inArray(u.id, r) > -1 ){
                ok = false;
                return false;
              }
            });
            return ok;
          }, a.items);
          if ( a.items.length ) {
            return a;
          }
          return null;
        }), 'text', 'asc');
      }
    },
    methods: {
      addUser(idUser, roleType){
        if ( this.canChange ){
          let exists = false;
          $.each(this.source.roles, (i, v) => {
            if ( $.inArray(idUser, v) > -1 ){
              exists = true;
              return false;
            }
          });
          if ( !exists ){
            bbn.fn.post(this.tasks.source.root + 'actions/role/insert', {
              id_task: this.source.id,
              role: roleType,
              id_user: idUser
            }, (d) => {
              if ( d.success ){
                this.source.roles[roleType].push(idUser);
              }
              else {
                bbn.fn.alert(bbn._('Error during user insert'));
              }
            });
          }
        }
      },
      removeUser(idUser, roleType){
        if ( this.canChange ){
          this.confirm(bbn._('Are you sure you want to remove this user?'), () => {
            const idx = $.inArray(idUser, this.source.roles[roleType]);
            if ( (idx > -1) ){
              bbn.fn.post(this.tasks.source.root + 'actions/role/delete', {
                id_task: this.source.id,
                role: roleType,
                id_user: idUser
              }, (d) => {
                if ( !d.success ){
                  bbn.fn.alert(bbn._('Error during the user remove'));
                  return false;
                }
                this.source.roles[roleType].splice(idx, 1);
              });
            }
          });
        }
        else {
          bbn.fn.alert(bbn._('You have no right to modify the roles in this task'));
          return false;
        }
      },
      dragEnd(d, ev){
        ev.preventDefault();
        if ( this.targetContainer ){
          if ( d.data.is_parent && Array.isArray(d.items) && d.items.length ){
            $.each(d.items, (i, v) => {
              if ( v.id ){
                this.addUser(v.id, this.targetContainer);
              }
            });
          }
          else if ( !d.data.is_parent && d.data.id ){
            this.addUser(d.data.id, this.targetContainer);
          }
        }
      },
      setTargetContainer(role){
        if ( this.canChange && this.$refs.task_usertree.realDragging && role ){
          this.targetContainer = role;
        }
      }
    }
  };
})();