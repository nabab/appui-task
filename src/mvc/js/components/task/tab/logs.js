/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 08/06/2017
 * Time: 19:38
 */
(() => {
  Vue.component('appui-task-tab-logs', {
    template: '#bbn-tpl-component-appui-task-tab-logs',
    props: ['source'],
    data(){
      return $.extend({}, this.source, {
        tableData: []
      });
    },
    methods: {
      readTable(){
        /** @todo add sort chrono desc */
        const vm = this;
        bbn.fn.post(vm.appui_tasks.root + 'panel/logs', {id_task: vm.id}, (d) => {
          vm.tableData = d.data || [];
        });
      },
      renderAvatar(val){
        return '<bbn-initial :user-id="' + val + '" :width="25" :height="25"></bbn-initial>';
      },
      renderUser(val){
        return this.appui_tasks.userName(val);
      },
      renderDate(val){
        return bbn.fn.fdate(val);
      }
    },
    mounted(){
      const vm = this;
      vm.readTable();
      vm.$nextTick(() => {
        $(vm.$el).bbn('analyzeContent', true);
      });
    }
  });
})();
