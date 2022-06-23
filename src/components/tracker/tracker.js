(() => {
  return {
    data(){
      return {
        list: [],
        isVisible: false,
        active: false,
        interval: false,
        progress: false,
        root: appui.plugins['appui-task'] + '/'
      }
    },
    computed:{
      realList(){
        if ( this.active ){
          return bbn.fn.order(this.list.filter(l => {
            return l.id !== this.active.id_task;
          }), 'title', 'ASC');
        }
        return this.list;
      }
    },
    methods: {
      getField: bbn.fn.getField,
      shorten: bbn.fn.shorten,
      openWindow(){
        if ( !this.isVisible && !this.list.length ){
          this.refreshList();
        }
        this.isVisible = !this.isVisible;
      },
      refreshList(startAfter){
        this.post(this.root + 'data/tasks', {
          id_user: appui.app.user.id
        }, d => {
          if ( d.success && (d.data.list !== undefined) && (d.data.active !== undefined) ){
            this.list = d.data.list;
            this.active = d.data.active || false
            if ( (startAfter !== undefined) && (typeof startAfter === 'string') ){
              this.start(startAfter);
            }
          }
        });
      },
      clearInt(){
        clearInterval(this.interval);
        this.interval = false;
        this.progress = false;
      },
      setInt(){
        this.interval = setInterval(() => {
          this.getProgress();
        }, 1000);
      },
      getProgress(){
        this.progress = this.secToTime(dayjs().unix() - dayjs(this.active.start).unix());
      },
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
      start(idTask){
        if (this.active) {
          this.confirm(bbn._('Are you sure you want to stop the tracker in progress') + '?', () => {
            this.stop();
          });
        }
        else {
          if (!bbn.fn.getRow(this.list, 'id', idTask)) {
            this.refreshList(idTask);
          }
          else {
            this.post(this.root + 'actions/tracker/start', {
              id_task: idTask
            }, d => {
              if (d.success && d.tracker) {
                let task = appui.getRegistered('appui-task-' + idTask);
                if (bbn.fn.isVue(task)) {
                  task.$set(task.source, 'tracker', d.tracker);
                }
                this.active = d.tracker;
              }
            });
          }
        }
      },
      stop(){
        if (this.active) {
          this.confirm(bbn._('Do you want to write a message?'), () => {
            this.getPopup().open({
              component: this.$options.components.trackerMessage,
              source: {
                message: ''
              },
              width: 800,
              height: 600,
              title: bbn._('Tracker message')
            });
          }, () => {
            this.stopPost();
          });
        }
      },
      stopPost(obj){
        obj = bbn.fn.extend({
          id_task: this.active.id_task
        }, obj);
        this.post(this.root + 'actions/tracker/stop', obj, d => {
          if (d.success) {
            let task = appui.getRegistered('appui-task-' + this.active.id_task);
            if (bbn.fn.isVue(task)) {
              task.$set(task.source, 'tracker', false);
              task.$set(task.source, 'trackers', d.trackers);
              if (!!obj.message) {
                let notes = task.find(task.dashboard.widgets.notes.component);
                if (bbn.fn.isVue(notes)) {
                  notes.getRef('forum').updateData();
                }
              }
            }
            this.clear();
          }
        });
      },
      openTask(idTask){
        this.isVisible = false;
        bbn.fn.link(this.root + 'page/task/' + idTask);
      },
      clear(){
        this.clearInt();
        this.progress = false;
        this.active = false;
      }
    },
    watch: {
      active(newVal){
        if ( newVal ){
          this.setInt();
        }
        else {
          this.clearInt();
        }
      }
    },
    created(){
      appui.register('appui-task-tracker', this);
    },
    mounted(){
      this.refreshList();
    },
    beforeDestroy(){
      this.clearInt();
      appui.unregister('appui-task-tracker');
    },
    components: {
      trackerMessage: {
        props: ['source'],
        template: `
<bbn-form
          :source="source"
          @submit="save"
>
  <bbn-textarea v-model="source.message"
                class="bbn-overlay"
                style="width: 100%; padding: 10px"
  ></bbn-textarea>
</bbn-form>
        `,
        data(){
          return {
            tracker: bbn.vue.find(appui, 'appui-task-tracker')
          }
        },
        methods: {
          save(){
            if ( this.source.message.length ){
              this.tracker.stopPost({message: this.source.message});
            }
          }
        }
      }
    }
  }
})();
