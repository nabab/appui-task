(() => {
  return {
    data(){
      return {
        selected: false,
        currentSection: 0,
        users: bbn.fn.map(bbn.fn.extend(true, [], appui.app.getActiveUsers()), u => {
          u.id = u.value;
          u.username = u.text
          return u;
        }),
        groups: appui.app.groups
      }
    },
    computed: {
      privileges(){
        return !!this.source.privileges ? bbn.fn.order(this.source.privileges, 'text') : [];
      },
      panelSource() {
        if (this.selected) {
          return [
            {
              header: '<span class="bbn-lg bbn-b">' + bbn._("Groups") + '</span>',
              component: 'appui-option-permissions-groups',
              componentOptions: {
                users: this.users,
                groups: this.groups,
                source: this.selected,
                parent: this
              }
            }, {
              header: '<span class="bbn-lg bbn-b">' + bbn._("Users") + '</span>',
              component: 'appui-option-permissions-users',
              componentOptions: {
                users: this.users,
                groups: this.groups,
                source: this.selected,
                parent: this
              }
            }
          ];
        }
        return [];
      },
    },
    methods: {
      
    },
    components: {
      privilege: {
        template: `
          <div class="bbn-spadded">
            <span v-text="source.text"/>
            <span class="bbn-s bbn-left-sspace bbn-secondary-text-alt"
                  v-text="source.code"/>
          </div>
        `,
        props: {
          source: {
            type: Object
          }
        }
      }
    }
  }
})();