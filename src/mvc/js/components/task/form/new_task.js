/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 14/06/2017
 * Time: 11:17
 */
(function(){
  Vue.component('appui-task-form-new_task', {
    template: '#bbn-tpl-component-appui-task-form-new_task',
    props: ['source'],
    data: function(){
      return $.extend({
        type: false
      }, this.source);
    },
    updated: function(){
      const vm = this;
      $(vm.$el).bbn('analyzeContent', true);
    },
    mounted: function(){
      const vm = this;
      vm.$nextTick(function(){
        setTimeout(function(){
          $(vm.$refs.new_task_form.$el).data('script', function(d){
            if ( d.success ){
              vm.$parent.close(vm.$parent.num-1);
              bbn.fn.link(vm.root + 'tasks/' + d.success);
            }
            else {
              bbn.fn.alert();
            }
          })
        }, 100);
      });
    }
  });
})();