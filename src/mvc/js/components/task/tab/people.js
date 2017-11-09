/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 08/06/2017
 * Time: 19:38
 */
(() => {
  Vue.component('appui-task-tab-people', {
    template: '#bbn-tpl-component-appui-task-tab-people',
    props: ['source'],
    data(){
      return {
        groups: appui.tasks.groups
      };
    },
    computed: {
      isMaster(){
        return bbn.env.userId === this.source.id_user;
      },
      isClosed(){
        return this.source.state === appui.tasks.source.states.closed;
      },
      isManager(){
        if ( this.isMaster ){
          return true;
        }
        return this.source.roles.managers && ($.inArray(bbn.env.userId, this.source.roles.managers) > -1);
      },
      canChange(){
        return !this.isClosed && this.isMaster;
      },
      managers(){
        if ( this.source.roles && this.source.roles.managers ){
          return $.map(this.source.roles.managers, (v) => {
            let user = bbn.fn.get_row(bbn.users, 'value', v);
              return user || undefined;
          });
        }
        return [];
      },
      viewers(){
        if ( this.source.roles && this.source.roles.viewers ){
          return $.map(this.source.roles.viewers, (v) => {
            let user = bbn.fn.get_row(bbn.users, 'value', v);
            return user || undefined;
          });
        }
        return [];
      },
      workers(){
        if ( this.source.roles && this.source.roles.workers ){
          return $.map(this.source.roles.workers, (v) => {
            let user = bbn.fn.get_row(bbn.users, 'value', v);
            return user || undefined;
          });
        }
        return [];
      }
    },
    methods: {
      filterTree(){
        this.$refs.task_usertree.widget.filterNodes((node) => {
          let ok = true;
          if ( !node.children && this.source.roles ){
            $.each(this.source.roles, (i, v) => {
              if ( $.inArray(node.data.id, v) > -1 ){
                ok = false;
              }
            });
          }
          return ok;
        });
      },
      addUser(node, cont){
        if ( this.canChange() ){
          let idUser = node.data.id,
              roleType = $(cont).attr("data-role-type"),
              exists = false;

          $.each(this.source.roles, (i, v) => {
            if ( $.inArray(idUser, v) > -1 ){
              exists = true;
              return false;
            }
          });
          if ( !exists ){
            if ( !this.source.roles[roleType] ){
              this.source.roles[roleType] = [];
            }
            if ( $.inArray(idUser, this.source.roles[roleType]) === -1 ){
              bbn.fn.post(appui.tasks.source.root + 'actions/role/insert', {
                id_task: this.source.id,
                role: roleType,
                id_user: idUser
              }, (d) => {
                if ( d.success ){
                  this.source.roles[roleType].push(idUser);
                  this[roleType].push(bbn.fn.get_row(bbn.users, 'id', idUser));
                  this.filterTree();
                }
                else {
                  bbn.fn.alert(bbn._('Error during user insert'));
                }
              });
            }
          }
        }
      },
      removeUser(idUser, roleType){
        if ( this.canChange() ){
          bbn.fn.confirm(bbn._('Are you sure you want to remove this user?'), () => {
            const idx = $.inArray(idUser, this.source.roles[roleType]);
            if ( !this.canChange() ){
              bbn.fn.alert(bbn._('You have no right to modify the roles in this task'));
              return;
            }
            if ( (idx > -1) ){
              bbn.fn.post(appui.tasks.source.root + 'actions/role/delete', {
                id_task: this.source.id,
                role: roleType,
                id_user: idUser
              }, (d) => {
                if ( !d.success ){
                  return bbn.fn.alert(bbn._('Error during the user remove'));
                }
                this.source.roles[roleType].splice(idx, 1);
                this[roleType].splice(bbn.fn.search(this[roleType], 'id', idUser), 1);
                this.filterTree();
              });
            }
          });
        }
      },
      liDraggable(){
        $("div.bbn-task-usertree li", this.$el).draggable({
          helper: "clone",
          containment: $(this.$el),
          scroll: false,
          start(e){
            let item = $.ui.fancytree.getNode(e.target);
            if (  item.children && !item.data.expanded ){
              item.setExpanded();
            }
          }
        });
      }
    },
    mounted(){
      this.liDraggable();
      //this.filterTree();
      $("div.bbn-task-assigned", this.$el).droppable({
        accept: ".bbn-task-usertree li",
        hoverClass: "bbn-dropable-hover",
        activeClass: "bbn-dropable-active",
        drop(e, ui){
          if ( this.canChange() ){
            let item = $.ui.fancytree.getNode(ui.draggable[0]),
                items = [];
            if ( item.children && item.children.length ){
              items = item.children;
            }
            else {
              items.push(item);
            }
            $.each(items, (i, v) => {
              this.addUser(v, $("div.k-content", e.target));
            });
          }
        }
      });
    }
  });
})();