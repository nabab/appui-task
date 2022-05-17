/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 08/06/2017
 * Time: 19:38
 */
((bbn) => {
  return {
    props: ['source'],
    data(){
      return {
        targetContainer: false,
        coloredContainers : false
      }
    },
    computed: {
      tasks(){
        return this.closest('appui-task-task').tasks;
      },
      isMaster(){
        return appui.app.user.id === this.source.id_user;
      },
      isClosed(){
        return this.source.state === this.tasks.source.states.closed;
      },
      isManager(){
        if ( this.isMaster ){
          return true;
        }
        return this.source.roles.managers && ( this.source.roles.managers.indexOf(appui.app.user.id) > -1 );
      },
      canChange(){
        return !this.isClosed && (this.isMaster || (!this.source.private && this.isManager));
      },
      managers(){
        if ( this.source.roles && this.source.roles.managers ){
          return bbn.fn.order(bbn.fn.map(this.source.roles.managers, v => {
            let user = bbn.fn.getRow(appui.app.users, 'value', v);
              return user || undefined;
          }), 'text', 'asc');
        }
        return [];
      },
      viewers(){
        if ( this.source.roles && this.source.roles.viewers ){
          //return bbn.fn.order($.map(this.source.roles.viewers, (v) => {
          return bbn.fn.order(bbn.fn.map(this.source.roles.viewers, v => {
            let user = bbn.fn.getRow(appui.app.users, 'value', v);
            return user || undefined;
          }), 'text', 'asc');
        }
        return [];
      },
      workers(){
        if ( this.source.roles && this.source.roles.workers ){
          //return bbn.fn.order($.map(this.source.roles.workers, (v) => {
          return bbn.fn.order(bbn.fn.map(this.source.roles.workers, v => {
            let user = bbn.fn.getRow(appui.app.users, 'value', v);
            return user || undefined;
          }), 'text', 'asc');
        }
        return [];
      },
      groupsFiltered(){
        return bbn.fn.order(
          bbn.fn.filter(
            bbn.fn.map(this.tasks.groups, g => {
              let a = bbn.fn.extend(true, {}, g);
              a.items = a.items.filter((u) => {
                let ok = true;
                bbn.fn.each(this.source.roles, (r, j) => {
                  if ((j !== 'deciders')
                    && ((u.id !== this.source.id_user) || (j !== 'managers'))
                    && r.includes(u.id)
                  ) {
                    ok = false;
                    return false;
                  }
                });
                return ok;
              });
              return a;
            }),
            g => {
              return !!g.items.length
            }
          ),
          'text', 'asc'
        );
      }
    },
    methods: {
      addUser(idUser, roleType){
        if (this.canChange && !!idUser && !!roleType) {
          let exists = false;
          if ( (idUser === this.source.id_user) && (roleType === 'managers') ){
            exists = true;
          }
          else {
            bbn.fn.each(this.source.roles, (v, i) => {
              if (
                ((idUser !== this.source.id_user) || (i !== 'managers')) &&
                //($.inArray(idUser, v) > -1)
                ( v.indexOf(idUser) > -1)
              ){
                exists = true;
                return false;
              }
            });
          }
          if ( !exists ){
            this.post(this.tasks.source.root + 'actions/role/insert', {
              id_task: this.source.id,
              role: roleType,
              id_user: idUser
            }, (d) => {
              if ( d.success ){
                this.source.roles[roleType].push(idUser);
              }
              else {
                this.alert(bbn._('Error during user insert'));
              }
            });
          }
        }
      },
      removeUser(idUser, roleType){
        if ( this.canChange ){
          this.confirm(bbn._('Are you sure you want to remove this user?'), () => {
            //const idx = $.inArray(idUser, this.source.roles[roleType]);
            const idx = this.source.roles[roleType].indexOf(idUser);
            if ( (idx > -1) ){
              this.post(this.tasks.source.root + 'actions/role/delete', {
                id_task: this.source.id,
                role: roleType,
                id_user: idUser
              }, (d) => {
                if ( !d.success ){
                  this.alert(bbn._('Error during the user remove'));
                  return false;
                }
                this.source.roles[roleType].splice(idx, 1);
                this.$nextTick(() => {
                  this.getRef('task_usertree').updateData();
                });
              });
            }
          });
        }
        else {
          this.alert(bbn._('You have no right to modify the roles in this task'));
          return false;
        }
      },
      dragEnd(d, ev){
        this.coloredContainers = false;
        this.targetContainer = false;
      },
      setTargetContainer(role){
        if ( this.canChange  && role && this.coloredContainers ){
          this.targetContainer = role;
        }
      },
      startDrag(){
        this.coloredContainers = true;
      },
      drop(ev){
        bbn.fn.log('drop', ev);
        ev.preventDefault();
        if (this.canChange) {
          let target = this.targetContainer,
              node = ev.detail.from.data.node;
          if (!!target && !!node) {
            if (!!node.numChildren
              && !!node.data
              && bbn.fn.isArray(node.data.items)
              && node.data.items.length
            ) {
              bbn.fn.each(node.data.items, v => {
                if (v.id) {
                  this.addUser(v.id, target);
                }
              });
            }
            else if (node.data.id_group && node.data.id) {
              this.addUser(node.data.id, target);
            }
            node.remove();
          }
        }
      }
    }
  };
})(window.bbn);