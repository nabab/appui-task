/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 08/06/2017
 * Time: 19:38
 */
(function(){
  Vue.component('appui-task-tab-logs', {
    template: '#bbn-tpl-component-appui-task-tab-logs',
    props: ['source'],
    data: function(){
      return $.extend({}, this.source, {
        tableData: []
      });
    },
    methods: {
      readTable: function(){
        /** @todo add sort chrono desc */
        var vm = this;
        bbn.fn.post(vm.appui_tasks.root + 'panel/logs', {id_task: vm.id}, function(d){
          vm.tableData = d.data || [];
        });
      },
      renderAvatar: function(val){
        return '<bbn-initial :user-id="val"></bbn-initial>';
      },
      renderUser: function(val){
        return this.appui_tasks.userName(val);
      },
      renderDate: function(val){
        return bbn.fn.fdate(val);
      }
    },
    mounted: function(){
      var vm = this;
      vm.readTable();
    }
  });
})();
