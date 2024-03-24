(() => {
  return {
    data(){
      return {
        root: appui.plugins['appui-task'] + '/',
        ready: false,
        currentSearch: '',
        selected: false,
        currentSection: 0,
        users: bbn.fn.map(bbn.fn.extend(true, [], appui.app.getActiveUsers()), u => {
          u.id = u.value;
          u.username = u.text
          return u;
        }),
        groups: appui.groups
      }
    },
    computed: {
      privileges(){
        return !!this.source.privileges ? bbn.fn.order(this.source.privileges, 'text') : [];
      },
      sections(){
        let sec = [];
        if (this.privileges && this.privileges.length) {
          bbn.fn.each(this.privileges, v => {
            sec.push({
              id: v.id,
              title: v.text,
              items: this.root + 'data/privileges',
              backgroundColor: v.backgroundColor || '',
              fontColor: v.color || v.fontColor || ''
            });
          })
        }
        return sec;
      }
    },
    methods: {
      isMobile: bbn.fn.isMobile,
      clearSearch(){
        if (this.currentSearch.length) {
          this.currentSearch = '';
        }
      },
      expandAll(){
        this.getRef('sections').expandAll();
      },
      collapseAll(){
        this.getRef('sections').collapseAll();
      },
      getFilters(src){
        let conditions = [{
          field: 'id',
          value: src.id
        }];
        if (this.currentSearch.length) {
          conditions.push({
            field: 'username',
            value: this.currentSearch
          });
        }
        return {
          conditions: conditions
        };
      }
    },
    created(){
      appui.register('appui-task-privileges', this);
    },
    mounted(){
      this.$nextTick(() => {
        this.ready = true;
      });
    },
    components: {
      user: {
        template: `
          <div class="bbn-alt-background bbn-spadded bbn-flex-width bbn-vmiddle bbn-radius">
            <div class="bbn-flex-fill bbn-background bbn-radius bbn-xspadded bbn-right-space">
              <div v-text="user.text"/>
              <div class="bbn-s bbn-secondary-text-alt bbn-top-xsspace"
                   v-text="group.text || group.name || group.nom"/>
            </div>
            <bbn-button class="bbn-no-border bbn-bg-red bbn-white"
                        icon="nf nf-fa-trash"
                        :notext="true"
                        @click="remove"/>
          </div>
        `,
        props: {
          source: {
            type: Object
          }
        },
        computed: {
          user(){
            return !!this.source.value ? bbn.fn.getRow(appui.users, 'value', this.source.value) : null;
          },
          group(){
            return !!this.user ? bbn.fn.getRow(appui.groups, 'id', this.user.id_group) : null;
          }
        },
        methods: {
          remove(){
            this.confirm(bbn._('Are you sure you want to remove this user?'), () => {
              let cl = this.closest('bbn-column-list');
              if (cl && !!cl.filters && cl.filters.conditions) {
                let id = bbn.fn.getField(cl.filters.conditions, 'value', 'field', 'id');
                if (id) {
                  this.post(appui.plugins['appui-task'] + '/actions/privileges/remove', {
                    idUser: this.source.value,
                    idPrivilege: id
                  }, d => {
                    if (d && d.success) {
                      cl.updateData();
                      appui.success();
                    }
                    else {
                      appui.error();
                    }
                  });
                }
              }
            })
          }
        }
      },
      toolbar: {
        template: `
          <div class="appui-task-privileges-toolbar">
            <bbn-button icon="nf nf-fa-plus"
                        :title="_('Add user')"
                        class="bbn-no-border bbn-right-sspace"
                        :notext="true"
                        @click="add"
                        v-if="!columnList.collapsed"/>
            <div :class="['bbn-radius', 'bbn-background', 'bbn-hspadded', {
                   'bbn-vspadded': columnList.collapsed,
                   'bbn-vmiddle': !columnList.collapsed,
                   'bbn-flex': columnList.collapsed,
                   'verticaltext': columnList.collapsed
                 }]"
                 style="height: auto; min-width: 2rem; align-items: center">
              <i class="nf nf-md-account_multiple bbn-m bbn-middle"/>
              <div :class="{'bbn-left-xsspace': !columnList.collapsed}"
                   v-text="columnList.total"/>
            </div>
          </div>
        `,
        props: {
          source: {
            type: Object
          }
        },
        data(){
          return {
            columnList: null
          }
        },
        methods: {
          add(){
            if (this.columnList
              && !!this.columnList.filters
              && this.columnList.filters.conditions
            ) {
              let id = bbn.fn.getField(this.columnList.filters.conditions, 'value', 'field', 'id');
              if (id) {
                let src = bbn.fn.map(this.columnList.currentData, d => d.data.value);
                this.getPopup({
                  component: appui.getRegistered('appui-task-privileges').$options.components.form,
                  source: {
                    users: src,
                    idPrivilege: id
                  },
                  title: bbn._('Select user(s)'),
                  width: 400,
                  height: 600
                });
              }
            }
          }
        },
        created(){
          this.$set(this, 'columnList', this.closest('bbn-column-list'));
        }
      },
      form: {
        template: `
          <bbn-form :action="root + 'actions/privileges/add'"
                    :scrollable="false"
                    class="bbn-overlay"
                    :source="source"
                    @success="onSuccess"
                    ref="form">
            <div class="bbn-overlay bbn-spadded">
              <appui-usergroup-picker :multi="true"
                                      v-model="source.users"
                                      :as-array="true"
                                      :source="users"
                                      :filterable="true"
                                      :selected-panel="true"
                                      :show-only-new="true"/>
            </div>
          </bbn-form>
        `,
        props: {
          source: {
            type: Object
          }
        },
        data(){
          let groupsUsers = [];
          let activeUsers = appui.app.getActiveUsers();
          if (appui.groups && activeUsers) {
            appui.groups.forEach(group => {
              let users = activeUsers.filter(u => {
                if (u.id_group === group.id) {
                  return !this.source.users.includes(u.value);
                }
                return false;
              });
              users = users.map(u => {
                let user = bbn.fn.extend(true, {
                  icon: 'nf nf-fa-user',
                  id: u.value
                }, u);
                delete user.value;
                return user;
              });
              if (users.length) {
                groupsUsers.push({
                  id: group.id,
                  text: group.nom || group.group,
                  items: bbn.fn.order(users, 'text'),
                  num: users.length,
                  icon: 'nf nf-fa-users'
                });
              }
            });
          }
          return {
            users: bbn.fn.order(groupsUsers, 'text'),
            root: appui.plugins['appui-task'] + '/',
            originalValue: bbn.fn.extend(true, [], this.source)
          }
        },
        methods: {
          onSuccess(d){
            if (d.success) {
              let main = appui.getRegistered('appui-task-privileges');
              if (main) {
                let list = main.findByKey(this.source.idPrivilege, 'bbn-column-list');
                if (list) {
                  list.updateData();
                  appui.success();
                }
              }
            }
            else {
              appui.error();
            }
          }
        }
      }
    }
  }
})();