/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 26/04/2018
 * Time: 10:50
 */
(() => {
  return {
    props: {
      source: {
        type: Object
      }
    },
    data(){
      return {
        progress: false,
        currentUserID: appui.user.id
      }
    },
    computed: {
      summary(){
        let roles = this.source.roles.managers.concat(this.source.roles.workers);
        let res = bbn.fn.map(
          bbn.fn.extend(true, [], bbn.fn.isArray(this.source.trackers) ? this.source.trackers : []),
          t => {
            let time = bbn.fn.isNumber(t.total_time) ? t.total_time : 0;
            if (!!this.progress && (t.id_user === appui.user.id)) {
              time += this.getProgress();
            }
            return {
              idUser: t.id_user,
              userName: this.userName(t.id_user),
              total: this.secToTime(time, true),
              notes: t.num_notes
            }
          }
        );
        /* bbn.fn.each(roles, r => {
          if (!bbn.fn.getRow(res, 'idUser', r)) {
            let time = 0;
            if (!!this.progress && (r === appui.user.id)) {
              time += this.getProgress();
            }
            res.push({
              idUser: r,
              userName: this.userName(r),
              total: this.secToTime(time, true),
              notes: 0
            });
          }
        }); */
        return bbn.fn.order(res, 'userName');
      }
    },
    methods: {
      clearTrackerInterval(){
        if (this.interval) {
          clearInterval(this.interval);
        }
      },
      setTrackerInterval(){
        this.interval = setInterval(() => {
          this.setProgress();
        }, 1000);
      },
      getProgress(){
        return dayjs().unix() - dayjs(this.source.tracker.start).unix();
      },
      setProgress(){
        this.progress = this.secToTime(this.getProgress());
      }
    },
    mounted(){
      if ( this.source.tracker ){
        this.setTrackerInterval();
      }
    },
    beforeDestroy(){
      this.clearTrackerInterval();
    },
    watch: {
      'source.tracker'(newVal){
        if ( newVal ){
          this.setTrackerInterval();
        }
        else {
          this.clearTrackerInterval();
          this.progress = false;
        }
      }
    }
  }
})();
