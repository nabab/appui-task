(() => {
  return {
    data(){
      return {
        root: appui.plugins['appui-task'] + '/'
      }
    },
    methods: {
      goToTrack(){
        if (!!this.source.idTrack) {
          let tracks = this.getRef('tracks');
          if (tracks) {
            tracks.scrollTo(this.source.idTrack);
          }
        }
      },
      onEdit(item){
        if (!!item.start && !!item.end) {
          if (dayjs(item.end).unix() < dayjs(item.start).unix()) {
            this.alert(bbn._('The end date must be more recent than the start date'));
            return false;
          }

          this.post(this.root + 'actions/tracker/edit', item, d => {
            if (d.success && d.data) {
              bbn.fn.iterate(d.data, (v, i) => {
                item[i] = v;
              });
              this.onEditSuccess();
            }
            else {
              this.onEditFailure();
            }
          });
        }
      },
      onEditSuccess(){
        appui.success();
      },
      onEditFailure(){
        appui.error();
      }
    },
    components: {
      editor: {
        template: `
          <bbn-form :source="source.row"
                    :data="source.data"
                    :action="root + 'actions/tracker/edit'"
                    :scrollable="false"
                    @success="success"
                    @failure="failure"
                    @cancel="cancel"
                    ref="form"
                    :validation="validation">
            <div class="bbn-padded bbn-grid-fields">
              <label class="bbn-label">` + bbn._('Start') + `</label>
              <bbn-datetimepicker v-model="source.row.start"
                                  :show-second="true"
                                  :max="maxStart"
                                  required/>
              <label class="bbn-label">` + bbn._('End') + `</label>
              <bbn-datetimepicker v-model="source.row.end"
                                  :show-second="true"
                                  :max="maxEnd"
                                  required/>
              <label class="bbn-label">`+ bbn._('Message') + `</label>
              <div style="height: 300px">
                <bbn-textarea v-model="source.row.message"
                              class="bbn-h-100"
                              style="width: 100%"/>
              </div>
            </div>
          </bbn-form>
        `,
        props: {
          source: {
            type: Object,
            required: true
          }
        },
        data(){
          return {
            root: appui.plugins['appui-task'] + '/',
            tracks: this.closest('bbn-floater').opener,
            maxEnd: dayjs().format('YYYY-MM-DD HH:mm:ss')
          }
        },
        computed: {
          maxStart(){
            if (!this.source.row.end) {
              return dayjs().format('YYYY-MM-DD HH:mm:ss');
            }

            let end = dayjs(this.source.row.end).unix(),
                now = dayjs().unix();
            return end < now ? this.source.row.end : dayjs().format('YYYY-MM-DD HH:mm:ss');
          }
        },
        methods: {
          validation(){
            if ( dayjs(this.source.row.end).unix() < dayjs(this.source.row.start).unix() ){
              this.alert(bbn._('The end date must be more recent than the start date'));
              return false;
            }
            return true;
          },
          success(d, e) {
            e.preventDefault();
            if (this.tracks.successEdit
              && bbn.fn.isFunction(this.tracks.successEdit)
              && this.tracks.successEdit(d)
            ) {
              this.tracks.getPopup().close();
              this.tracks.updateData();
            }
          },
          failure(d) {
            this.tracks.$emit('editFailure', d);
          },
          cancel() {
            if (this.tracks
              && bbn.fn.isFunction(this.tracks.cancel)
            ) {
              this.tracks.cancel();
            }
          }
        }
      }
    }
  }
})();