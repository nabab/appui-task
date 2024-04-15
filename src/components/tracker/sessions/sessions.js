(() => {
  return {
    mixins: [bbn.cp.mixins.basic],
    data(){
      return {
        root: appui.plugins['appui-task'] + '/'
      }
    },
    methods: {
      goToTrack(){
        if (!!this.source?.idTrack) {
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
      },
      mapItems(item){
        if (!!item.message) {
          item.title += '<div class="bbn-alt-text bbn-top-sspace">' + item.message + '</div>';
        }
        else {
          item.message = '';
        }
        return item;
      }
    },
    created(){
      appui.register('appui-task-sessions-editor', this);
    },
    components: {
      popupEditor: {
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
              <bbn-datetimepicker bbn-model="source.row.start"
                                  :show-second="true"
                                  :max="maxStart"
                                  required/>
              <label class="bbn-label">` + bbn._('End') + `</label>
              <bbn-datetimepicker bbn-model="source.row.end"
                                  :show-second="true"
                                  :max="maxEnd"
                                  required/>
              <label class="bbn-label">`+ bbn._('Message') + `</label>
              <div style="height: 300px">
                <bbn-textarea bbn-model="source.row.message"
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
      },
      toolbarEditor: {
        template: `
          <bbn-form :source="source"
                    :data="getData()"
                    :action="appuiTracks.root + 'actions/tracker/edit'"
                    :scrollable="false"
                    @success="onSuccess"
                    @failure="onFailure"
                    @cancel="onCancel"
                    ref="form"
                    :buttons="[]"
                    @hook:mounted="setForm"
                    :validation="validation">
            <div class="bbn-hspadded bbn-bottom-spadded bbn-vmiddle bbn-flex-width"
                 style="gap: var(--space); align-items: flex-end">
              <div class="bbn-flex-fill bbn-vmiddle bbn-flex-width"
                   style="gap: var(--space); align-items: flex-end; flex-wrap: wrap">
                <span>
                  <span class="bbn-toplabel">` + bbn._('Start') + `</span>
                  <bbn-datetimepicker bbn-model="source.start"
                                      :show-second="true"
                                      :max="maxStart"
                                      required/>
                </span>
                <span>
                  <span class="bbn-toplabel">` + bbn._('End') + `</span>
                  <bbn-datetimepicker bbn-model="source.end"
                                      :show-second="true"
                                      :max="maxEnd"
                                      required/>
                </span>
                <span>
                  <span class="bbn-toplabel">` + bbn._('Task') + `</span>
                  <bbn-dropdown bbn-model="source.id_task"
                                :source="appuiTracks.root + '/data/list'"
                                :limit="0"
                                :filterable="true"
                                :filters="{
                                  conditions: [{
                                    field: 'bbn_tasks.state',
                                    value: appuiTracks.source.states.ongoing
                                  }]
                                }"
                                :sortable="true"
                                :order="[{
                                  field: 'bbn_notes_versions.title',
                                  dir: 'ASC'
                                }]"
                                source-value="id"
                                source-text="title"
                                required/>
                </span>
                <div class="bbn-flex-fill"
                     style="max-height: 3.2rem; z-index: 1">
                  <span>
                    <span class="bbn-toplabel">`+ bbn._('Message') + `</span>
                    <bbn-textarea bbn-model="source.message"
                                  :resizable="false"
                                  rows="1"
                                  class="bbn-w-100"
                                  @focus="textareaHeight = '20rem'"
                                  @blur="textareaHeight = 'auto'"
                                  :style="{height: textareaHeight}"/>
                  </span>
                </div>
                <div class="bbn-flex"
                     style="gap: var(--sspace)">
                  <bbn-button @click="save"
                              text="` + bbn._('Save') + `"
                              :notext="true"
                              icon="nf nf-fa-check_circle"
                              class="bbn-primary bbn-xl"
                              :disabled="!form || !form.canSubmit"/>
                  <bbn-button @click="cancel"
                              text="` + bbn._('Cancel') + `"
                              :notext="true"
                              icon="nf nf-fa-times_circle"
                              class="bbn-xl"/>
                </div>
              </div>
              <bbn-button @click="remove"
                          icon="nf nf-fa-trash"
                          class="bbn-bg-red bbn-white bbn-xl"
                          :notext="true"
                          text="` + bbn._('Delete') + `"/>
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
            appuiTracks: appui.getRegistered('appui-task-sessions-editor'),
            tracks: this.closest('bbn-tracks'),
            form: false,
            maxEnd: dayjs().format('YYYY-MM-DD HH:mm:ss'),
            textareaHeight: 'auto'
          }
        },
        computed: {
          maxStart(){
            if (!this.source.end) {
              return dayjs().format('YYYY-MM-DD HH:mm:ss');
            }

            let end = dayjs(this.source.end).unix(),
                now = dayjs().unix();
            return end < now ? this.source.end : dayjs().format('YYYY-MM-DD HH:mm:ss');
          }
        },
        methods: {
          setForm(){
            this.form = this.getRef('form');
          },
          getData(){
            return bbn.fn.isFunction(this.tracks.data) ? this.tracks.data() : this.tracks.data;
          },
          validation(){
            if (dayjs(this.source.end).unix() < dayjs(this.source.start).unix()) {
              this.alert(bbn._('The end date must be more recent than the start date'));
              return false;
            }

            return true;
          },
          save(){
            if (this.form) {
              this.form.submit();
            }
          },
          cancel(){
            if (this.form) {
              this.form.cancel();
            }
          },
          remove(){
            this.confirm(bbn._('Are you sure you want to delete this item?'), () => {
              this.post(this.appuiTracks.root + 'actions/tracker/remove', {
                id: this.source.id
              }, d => {
                if (d.success) {
                  this.tracks.updateData();
                  this.tracks.editedRow = false;
                }
              });
            });
          },
          onSuccess(d, e) {
            e.preventDefault();
            if (this.tracks.successEdit
              && bbn.fn.isFunction(this.tracks.successEdit)
              && this.tracks.successEdit(d)
            ) {
              this.tracks.getPopup().close();
              this.tracks.updateData();
            }
          },
          onFailure(d) {
            this.tracks.$emit('editFailure', d);
          },
          onCancel() {
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