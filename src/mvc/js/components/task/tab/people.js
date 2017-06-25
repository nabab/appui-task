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
      let vm = this,
          managers = [],
          viewers = [],
          workers = [];

      if ( vm.source.roles ){
        $.each(vm.source.roles, (i, v) => {
          $.each(v, (k, e) => {
            let user = bbn.fn.get_row(bbn.users, 'id', e);
            if ( user ){
              eval(i).push(user);
            }
          });
        });
      }
      return $.extend({
        managers: managers,
        viewers: viewers,
        workers: workers
      }, vm.source);
    },
    methods: {
      isMaster(){
        if ( this.isManager() ){
          return true;
        }
        return bbn.env.userId === this.id_user;
      },
      isClosed(){
        return this.state === this.appui_tasks.states.closed;
      },
      isManager(){
        let managers = this.roles.managers;
        return managers && ($.inArray(bbn.env.userId, managers) > -1);
      },
      canChange(){
        return !this.isClosed() && this.isMaster();
      },
      filterTree(){
        const vm = this;
        vm.$refs.task_usertree.widget.filterNodes((node) => {
          let ok = true;
          if ( !node.children && vm.roles ){
            $.each(vm.roles, (i, v) => {
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
          let vm = this,
              idUser = node.data.id,
              roleType = $(cont).attr("data-role-type"),
              exists = false;

          $.each(vm.roles, (i, v) => {
            if ( $.inArray(idUser, v) > -1 ){
              exists = true;
              return false;
            }
          });
          if ( !exists ){
            if ( !vm.roles[roleType] ){
              vm.roles[roleType] = [];
            }
            if ( $.inArray(idUser, vm.roles[roleType]) === -1 ){
              bbn.fn.post(vm.appui_tasks.root + 'actions/role/insert', {
                id_task: vm.id,
                role: roleType,
                id_user: idUser
              }, (d) => {
                if ( d.success ){
                  vm.roles[roleType].push(idUser);
                  vm[roleType].push(bbn.fn.get_row(bbn.users, 'id', idUser));
                  vm.filterTree();
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
            const vm = this,
                  idx = $.inArray(idUser, vm.roles[roleType]);
            if ( !vm.canChange() ){
              bbn.fn.alert(bbn._('You have no right to modify the roles in this task'));
              return;
            }
            if ( (idx > -1) ){
              bbn.fn.post(vm.appui_tasks.root + 'actions/role/delete', {
                id_task: vm.id,
                role: roleType,
                id_user: idUser
              }, (d) => {
                if ( !d.success ){
                  return bbn.fn.alert(bbn._('Error during the user remove'));
                }
                vm.roles[roleType].splice(idx, 1);
                vm[roleType].splice(bbn.fn.search(vm[roleType], 'id', idUser), 1);
                vm.filterTree();
              });
            }
          });
        }
      },
      liDraggable(){
        const vm = this;
        $("div.bbn-task-usertree li", vm.$el).draggable({
          helper: "clone",
          containment: $(vm.$el),
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
      const vm = this;

      vm.liDraggable();
      vm.filterTree();
      $("div.bbn-task-assigned", vm.$el).droppable({
        accept: ".bbn-task-usertree li",
        hoverClass: "bbn-dropable-hover",
        activeClass: "bbn-dropable-active",
        drop(e, ui){
          if ( vm.canChange() ){
            let item = $.ui.fancytree.getNode(ui.draggable[0]),
                items = [];
            if ( item.children && item.children.length ){
              items = item.children;
            }
            else {
              items.push(item);
            }
            $.each(items, (i, v) => {
              vm.addUser(v, $("div.k-content", e.target));
            });
          }
        }
      });
      vm.$nextTick(() => {
        $(vm.$el).bbn('analyzeContent', true);
      });
    }
  });
})();