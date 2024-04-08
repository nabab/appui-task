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
        let d = dayjs.duration((bbn.fn.sum(this.summary, 'totalTime') || 0) * 1000);
        let res = '';
        if (d.years()) {
          let y = d.years();
          res += '<strong>' + y + '</strong>' + (y === 1 ? bbn._('year') : bbn._('years'));
        }
        if (d.months()) {
          let m = d.months();
          res += (!!res.length ? ' ' : '') + '<strong>' + m + '</strong>' + (m === 1 ? bbn._('month') : bbn._('months'));
        }
        if (d.days()) {
          let da = d.days();
          res += (!!res.length ? ' ' : '') + '<strong>' + da + '</strong>' + (da === 1 ? bbn._('day') : bbn._('days'));
        }
        if (d.hours()) {
          let h = d.hours();
          res += (!!res.length ? ' ' : '') + '<strong>' + h + '</strong>' + (h === 1 ? bbn._('hour') : bbn._('hours'));
        }
        if (d.minutes()) {
          let m = d.minutes();
          res += (!!res.length ? ' ' : '') +'<strong>' +  m + '</strong>' + (m === 1 ? bbn._('minute') : bbn._('minutes'));;
        }

        return res;
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
