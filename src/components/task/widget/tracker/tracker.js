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
      totalTime(){
        let d = bbn.fn.sum(this.summary, 'totalTime') || 0;
        return this.secToTime(d, true);
      },
      totalTokens(){
        if (!this.source.trackers) {
          return 0;
        }

        return bbn.fn.sum(this.source.trackers, 'total_tokens') || 0;
      },
      summary(){
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
              totalTime: time,
              notes: t.num_notes
            }
          }
        );
        return bbn.fn.order(res, 'userName');
      }
    },
    methods: {
      secToTime(seconds, cut){
        let h = Math.floor(seconds / 3600),
            m,
            s;

        seconds %= 3600;
        m = Math.floor(seconds / 60);
        s = seconds % 60;
        h = (h < 10) ? '0' + h : h;
        m = (m < 10) ? '0' + m : m;
        s = (s < 10) ? '0' + s : s;
        return h + ':' + m + (cut ? '' : ':'+ s);
      },
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
        return bbn.dt().unix() - bbn.dt(this.source.tracker.start).unix();
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
