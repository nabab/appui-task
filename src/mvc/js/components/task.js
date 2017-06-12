/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 07/06/2017
 * Time: 12:06
 */
(function(){
  Vue.component('appui-task', {
    template: '#bbn-tpl-component-appui-task',
    props: ['source'],
    data: function(){
      return $.extend(this.source, {appui_tasks: $.extend({}, appui.tasks)});
    }
  });
})();
