(() => {
  let mixins = [{
    data(){
      return {
        mainPage: {},
        homePage: {},
        columnsComp: {}
      };
    },
    computed:{
      root(){
        return !!this.mainPage ? this.mainPage.root : '';
      }
    },
    created(){
      this.$set(this, 'mainPage', this.closest('appui-task'));
      this.$set(this, 'homePage', this.closest('appui-task-home'));
      this.$set(this, 'columnsComp', this.closest('appui-task-columns'));
    }
  }];
  bbn.vue.addPrefix('appui-task-item-', (tag, resolve, reject) => {
    return bbn.vue.queueComponent(
      tag,
      appui.plugins['appui-task'] + '/components/item/' + bbn.fn.replaceAll('-', '/', tag).substr('appui-task-item-'.length),
      mixins,
      resolve,
      reject
    );
  });

  return {
    mixins: [...mixins, appuiTaskMixin],
    props: {
      source: {
        type: Object
      },
      index:{
        type: Number
      },
      inverted: {
        type: Boolean,
        default: false
      },
      showParent: {
        type: Boolean,
        default: false
      }
    },
    data(){
      return {
        showOpenContent: false,
        showSubtasks: false,
        collapsed: false
      }
    },
    computed: {
      root(){
        return !!this.mainPage ? this.mainPage.root : '';
      },
      privileges(){
        return!!this.mainPage ? this.mainPage.privileges : {};
      },
      author(){
        return !!this.mainPage ? this.mainPage.userName(this.source.id_user) : '';
      },
      colObj(){
        return !!this.columnsComp ? bbn.fn.getRow(this.closest('column').filteredData, 'index', this.index) : {};
      },
      statusBgColor(){
        return !!this.mainPage ? this.mainPage.getStatusBgColor(this.mainPage.getStatusCode(this.source.state)) : '';
      },
      statusColor(){
        return !!this.mainPage ? this.mainPage.getStatusColor(this.mainPage.getStatusCode(this.source.state)) : '';
      },
      closedChildren(){
        return !!this.mainPage ? bbn.fn.filter(this.source.children, c => c.state === this.mainPage.states.closed) : [];
      },
      managersTitle(){
        if (this.mainPage) {
          let s = bbn.fn.getField(this.mainPage.optionsRoles, 'text', 'code', 'managers');
          if (!!this.source.roles.managers) {
            s += "\n";
            bbn.fn.each(this.source.roles.managers, u => {
              s += "\n" + appui.app.getUserName(u);
            });
          }
          return s;
        }
        return '';
      },
      workersTitle(){
        if (this.mainPage) {
          let s = bbn.fn.getField(this.mainPage.optionsRoles, 'text', 'code', 'workers');
          if (!!this.source.roles.workers) {
            s += "\n";
            bbn.fn.each(this.source.roles.workers, u => {
              s += "\n" + appui.app.getUserName(u);
            });
          }
          return s;
        }
        return '';
      },
      role(){
        if (this.mainPage && !!this.source.role) {
          return bbn.fn.getRow(this.mainPage.optionsRoles, 'value', this.source.role);
        }
        return false;
      },
      isCollapsed(){
        if (this.colObj && bbn.fn.numProperties(this.colObj)) {
          return !!this.colObj.collapsed;
        }
        return this.collapsed;
      },
      canChangeStatus(){
        return this.canReopen
          || (this.canChange
            && ((this.isUnapproved && this.canClose)
              || (this.isActive
                && (this.canStart || this.canHold || this.canResume || this.canClose)
                  || (this.isHolding && this.canResume)
                  || (this.isClosed && this.canReopen))))
      }
    },
    methods: {
      getMenuSource(){
        let menu = [{
          text: bbn._('Open in new window'),
          icon: 'nf nf-mdi-open_in_new',
          action: this.seeTask
        }, {
          text: this.isCollapsed ? bbn._('Expand') : bbn._('Collapse'),
          icon: this.isCollapsed ? 'nf nf-mdi-arrow_expand' : 'nf nf-mdi-arrow_collapse',
          action: this.toggleCollapsed
        }];
        if (this.source.num_notes) {
          menu.push({
            text: bbn._('Open notes in new window'),
            icon: 'nf nf-mdi-comment_multiple_outline',
            action: this.openNotes
          });
        }
        let status = this.getStatusSource();
        if (status.length) {
          menu.push({
            group: bbn._('Status')
          }, ...status);
        }
        if (this.canChange) {
          menu.push({
            text: bbn._('Edit title'),
            icon: 'nf nf-mdi-format_title',
            action: this.editTitle
          })
        }
        if ((this.isAdmin || this.isProjectManager)
          && ((this.isClosed && this.source.price) || !this.isClosed)
        ) {
          menu.push({
            text: !!this.source.price ? bbn._('Edit budget') : bbn._('Add budget'),
            icon: 'nf nf-fa-money',
            action: this.editBudget
          });
          if (!!this.source.price) {
            menu.push({
              text: bbn._('Remove budget'),
              icon: 'nf nf-mdi-delete_empty',
              action: this.removeBudget
            });
          }
        }
        if (this.canChange) {
          menu.push({
            group: bbn._('Roles')
          }, {
            text: bbn._('Add or remove managers'),
            icon: 'nf nf-mdi-account_star',
            action: () => {
              this.manageRole('managers');
            },
            backgroundColor: this.mainPage.getRoleBgColor('managers'),
            color: this.mainPage.getRoleColor('managers')
          }, {
            text: bbn._('Add or remove workers'),
            icon: 'nf nf-mdi-worker',
            action: () => {
              this.manageRole('workers');
            },
            backgroundColor: this.mainPage.getRoleBgColor('workers'),
            color: this.mainPage.getRoleColor('workers')
          }, {
            text: bbn._('Add or remove viewers'),
            icon: 'nf nf-fa-user_secret',
            action: () => {
              this.manageRole('viewers');
            },
            backgroundColor: this.mainPage.getRoleBgColor('viewers'),
            color: this.mainPage.getRoleColor('viewers')
          });
          if (this.canChangeDecider) {
            menu.push({
              text: bbn._('Add or remove deciders'),
              icon: 'nf nf-fa-gavel',
              action: () => {
                this.manageRole('deciders');
              },
              backgroundColor: this.mainPage.getRoleBgColor('deciders'),
              color: this.mainPage.getRoleColor('deciders')
            });
          }
        }
        return menu;
      },
      getStatusSource(){
        let menu = [];
        if (this.canChangeStatus) {
          if (this.isActive && !this.isUnapproved && this.canStart) {
            menu.push({
              text: bbn._('Put on ongoing'),
              icon: 'nf nf-fa-play',
              action: this.start,
              backgroundColor: this.mainPage.getStatusBgColor('ongoing'),
              color: this.mainPage.getStatusColor('ongoing')
            });
          }
          if (this.isActive && !this.isUnapproved && this.canHold) {
            menu.push({
              text: bbn._('Put on hold'),
              icon: 'nf nf-fa-pause',
              action: this.hold,
              backgroundColor: this.mainPage.getStatusBgColor('holding'),
              color: this.mainPage.getStatusColor('holding')
            });
          }
          if ((this.isActive || this.isHolding) && !this.isUnapproved && this.canResume) {
            menu.push({
              text: bbn._('Resume'),
              icon: 'nf nf-fa-play',
              action: this.resume,
              backgroundColor: this.mainPage.getStatusBgColor('ongoing'),
              color: this.mainPage.getStatusColor('ongoing')
            });
          }
          if ((this.isActive || this.isUnapproved) && this.canClose) {
            menu.push({
              text: bbn._('Close'),
              icon: 'nf nf-fa-check',
              action: this.close,
              backgroundColor: this.mainPage.getStatusBgColor('closed'),
              color: this.mainPage.getStatusColor('closed')
            });
          }
          if (this.isClosed && this.canReopen) {
            menu.push({
              text: bbn._('Reopen'),
              icon: 'nf nf-oct-issue_reopened',
              action: this.reopen,
              backgroundColor: this.mainPage.getStatusBgColor('opend'),
              color: this.mainPage.getStatusColor('opend')
            });
          }
        }
        return menu;
      },
      manageRole(role){
        if (this.canChange && !!role) {
          this.getPopup({
            component: 'appui-task-form-role',
            componentOptions: {
              source: this.source,
              role: role,
              manage: true
            },
            title: bbn._('Select user(s)'),
            width: 400,
            height: 600
          });
        }
      },
      seeTask(){
        bbn.fn.link(this.mainPage.root + 'page/task/' + this.source.id);
      },
      seeParentTask(){
        if (!!this.source.parent && !!this.source.parent.id) {
          bbn.fn.link(this.mainPage.root + 'page/task/' + this.source.parent.id);
        }
      },
      editTitle(){
        this.getPopup({
          title: false,
          closable: false,
          width: bbn.fn.isMobile() ? '95%' : '90%',
          height: bbn.fn.isMobile() ? '95%' : '90%',
          component: 'appui-task-item-title',
          source: this.source
        });
      },
      openDescription(){
        this.getPopup({
          title: false,
          closable: false,
          width: bbn.fn.isMobile() ? '95%' : '90%',
          height: bbn.fn.isMobile() ? '95%' : '90%',
          component: 'appui-task-item-description',
          source: this.source
        });
      },
      openNotes(){
        if (this.source.num_notes) {
          this.getPopup({
            title: false,
            closable: false,
            width: bbn.fn.isMobile() ? '95%' : '90%',
            height: bbn.fn.isMobile() ? '95%' : '90%',
            component: 'appui-task-item-notes',
            source: this.source
          });
        }
      },
      editBudget(){
        
      },
      removeBudget(){

      },
      toggleSubtasks(){
        this.showSubtasks = !!this.source.num_children && !this.showSubtasks;
      },
      toggleCollapsed(){
        if (this.colObj && bbn.fn.numProperties(this.colObj)) {
          this.$set(this.colObj, 'collapsed', !!this.colObj.collapsed ? false : true);
          this.collapsed = this.colObj.collapsed;
        }
        else {
          this.collapsed = !this.collapsed;
        }
      }
    },
    mounted(){
      this.$nextTick(() => {
        if (this.getRef('description').clientHeight >= 600) {
          this.showOpenContent = true;
        }
      });
    }
  }
})();