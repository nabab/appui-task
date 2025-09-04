/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 26/04/2018
 * Time: 10:35
 */
 (() => {
  return {
    mixins: [bbn.cp.mixins.basic],
    props: {
      source: {
        type: Object
      }
    },
    data(){
      return {
        root: appui.plugins['appui-task'] + '/',
        mainPage: appui.getRegistered('appui-task'),
        imageDom: appui.plugins['appui-note'] + '/media/image/'
      }
    },
    computed: {
      form(){
        return {
          imageDom: this.root + 'image/tmp/',
          fileSave: this.root + 'actions/file/upload/',
          fileRemove: this.root + 'actions/file/unupload/',
          linkPreview: this.root + 'link_preview',
          data: {
            id_task: this.source.id
          }
        }
      },
      isClosed() {
        return this.mainPage
          && (this.source.state === this.mainPage.source.states.closed);
      }
    },
    methods: {
      insert(){
        if (!this.isClosed) {
          const cp = this;
          this.getPopup({
            label: bbn._('New message'),
            width: 800,
            height: 750,
            component: 'appui-note-forum-form',
            componentOptions: bbn.fn.extend(true, {
              formAction: this.root + 'actions/messages/insert',
              source: {
                title: '',
                text: '',
                files: [],
                links: [],
                locked: 1
              }
            }, this.form),
            componentEvents: {
              success: d => {
                if (d.success) {
                  cp.getRef('forum').updateData();
                  appui.success(bbn._('Inserted'));
                }
                else {
                  appui.error(bbn._('Error'));
                }
              }
            }
          });
        }
      },
      edit(n, v){
        if (!this.isClosed) {
          const cp = this;
          this.getPopup({
            label: bbn._('Edit'),
            width: 800,
            height: 600,
            component: 'appui-note-forum-form',
            componentOptions: bbn.fn.extend(true, {
              formAction: this.root + 'actions/messages/edit',
              data: {
                id: n.id,
                id_task: this.source.id
              },
              source: {
                title: !v.isTopic ? undefined : n.title,
                text: n.content,
                files: n.files,
                links: n.links,
                locked: n.locked
              }
            }, this.form),
            componentEvents: {
              success: d => {
                if (d.success) {
                  if (!v.isTopic) {
                    v.topic.updateData();
                  }
                  else {
                    cp.getRef('forum').updateData();
                  }
                  appui.success(bbn._('Edited'));
                }
                else {
                  appui.error(bbn._('Error'));
                }
              }
            }
          });
        }
      },
      reply(n, v){
        if (!this.isClosed) {
          this.getPopup({
            label: bbn._('Reply to') + ' ' + appui.getUserName(n.creator),
            width: 800,
            height: 600,
            component: 'appui-note-forum-form',
            componentOptions: bbn.fn.extend(true, {
              formAction: this.root + 'actions/messages/reply',
              data: {
                id_parent: n.id,
                id_alias: n.id_alias || n.id,
                id_task: this.source.id
              },
              source: {
                text: '',
                files: [],
                links: [],
                locked: 1
              }
            }, this.form),
            componentEvents: {
              success: d => {
                if (d.success) {
                  if (!v.isTopic) {
                    v.topic.updateData();
                  }
                  else {
                    n.num_replies++;
                    v.topic.updateData();
                  }
                  appui.success(bbn._('Inserted'));
                }
                else {
                  appui.error(bbn._('Error'));
                }
              }
            }
          });
        }
      },
      removeItem(n, v){
        if (!this.isClosed) {
          this.confirm(bbn._('Are you sure you want to delete this message?'), () => {
            this.post(this.root + 'actions/messages/delete', {
              id: n.id,
              id_task: this.source.id
            }, d => {
              if (d.success) {
                if (!v.isTopic) {
                  v.topic.updateData();
                }
                else {
                  this.getRef('forum').updateData();
                }
                appui.success(bbn._('Deleted'));
              }
              else {
                appui.error(bbn._('Error'));
              }
            });
          });
        }
      }
    }
  }
})();
