/**
 * Created by BBN on 27/04/2017.
 */
(() => {
  return {
    data(){
      return {
        tasksRoot: appui.plugins['appui-task'] + '/'
      }
    },
    computed: {
      date(){
        return bbn.fn.fdate(this.source.last_activity);
      }
    }
  }
})();
