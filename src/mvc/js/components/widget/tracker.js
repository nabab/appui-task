/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 26/04/2018
 * Time: 10:50
 */
(() => {
  return {
    props: ['source'],
    data(){
      return {
        main: this.closest('appui-task-tab-main'),
        interval: false,
        progress: false
      }
    },
    computed: {
      summary(){
        let trackers = [],
            ret = [];
        if ( Array.isArray(this.source.trackers) ){
          trackers = this.source.trackers.filter(t => {
            return (this.source.roles.workers.indexOf(t.id_user) > -1) ||
              (this.source.roles.managers.indexOf(t.id_user) > -1);
          });
        }
        ret = this.source.roles.workers.map(w => {
          let totUser = bbn.fn.get_field(trackers, 'id_user', w, 'total_time');
          totUser = this.trackerComp.secToTime((totUser !== null) && (totUser !== false) ? totUser : 0, true);
          return {
            idUser: w,
            userName: this.main.tasks.userName(w),
            summary: totUser
          }
        });
        ret = ret.concat(this.source.roles.managers.map(w => {
          let totUser = bbn.fn.get_field(trackers, 'id_user', w, 'total_time');
          totUser = this.trackerComp.secToTime((totUser !== null) && (totUser !== false) ? totUser : 0, true);
          return {
            idUser: w,
            userName: this.main.tasks.userName(w),
            summary: totUser
          }
        }));
        return bbn.fn.order(ret, 'userName', 'ASC');
      },
      trackerComp(){
        return bbn.vue.find(appui, 'appui-task-tracker');
      }
    },
    methods: {
      start(){
        if (
          !this.source.tracker &&
          this.main.isOngoing &&
          (this.main.isWorker || this.main.isManager) &&
          this.trackerComp
        ){
          this.trackerComp.start(this.source.id);
        }
      },
      clearInt(){
        clearInterval(this.interval);
        this.interval = false;
      },
      setInt(){
        this.interval = setInterval(() => {
          this.getProgress();
        }, 1000);
      },
      getProgress(){
        this.progress = this.trackerComp.secToTime(moment().unix() - moment(this.source.tracker.start).unix());
      }
    },
    watch: {
      'source.tracker'(newVal){
        if ( newVal ){
          this.setInt();
        }
        else {
          this.clearInt();
          this.progress = false;
        }
      }
    },
    mounted(){
      if ( this.source.tracker ){
        this.setInt();
      }
    },
    beforeDestroy(){
      this.clearInt();
    }
  }
})();
