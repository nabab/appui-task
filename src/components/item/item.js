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
      this.$set(this, 'mainPage', appui.getRegistered('appui-task'));
      this.$set(this, 'homePage', this.closest('appui-task-home'));
      this.$set(this, 'columnsComp', this.closest('appui-task-columns'));
    }
  }];
  bbn.cp.addPrefix('appui-task-item-', null, mixins);

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
        type: [Boolean, Number],
        default: false
      },
      showParent: {
        type: [Boolean, Number],
        default: false
      },
      editable: {
        type: [Boolean, Number],
        default: true
      },
      removeParent: {
        type: [Boolean, Number],
        default: false
      },
      isSub: {
        type: [Boolean, Number],
        default: false
      },
      collapseFooter: {
        type: Boolean,
        default: true
      },
      forceCollapsed: {
        type: Boolean,
        default: false
      }
    },
    data(){
      return {
        showOpenContent: false,
        showSubtasks: false,
        collapsed: !this.editable || !!this.forceCollapsed,
        currentFilters: {
          logic: 'AND',
          conditions: [{
            field: 'id_parent',
            value: this.source.id
          }]
        },
        currentOrder: [{
          field: 'last_action',
          dir: 'DESC'
        }],
        budgetContextButton: '',
        priorityContextButton: '',
        statusContextButton: '',
        menuContextButton: '',
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
        return !!this.columnsComp ? bbn.fn.getRow(this.closest('bbn-kanban-element').filteredData, 'index', this.index) : {};
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
              s += "\n" + appui.getUserName(u);
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
              s += "\n" + appui.getUserName(u);
            });
          }
          return s;
        }
        return '';
      },
      viewersTitle(){
        if (this.mainPage) {
          let s = bbn.fn.getField(this.mainPage.optionsRoles, 'text', 'code', 'viewers');
          if (!!this.source.roles.viewers) {
            s += "\n";
            bbn.fn.each(this.source.roles.viewers, u => {
              s += "\n" + appui.getUserName(u);
            });
          }
          return s;
        }
        return '';
      },
      decidersTitle(){
        if (this.mainPage) {
          let s = bbn.fn.getField(this.mainPage.optionsRoles, 'text', 'code', 'deciders');
          if (!!this.source.roles.deciders) {
            s += "\n";
            bbn.fn.each(this.source.roles.deciders, u => {
              s += "\n" + appui.getUserName(u);
            });
          }
          return s;
        }
        return '';
      },
      userRole(){
        if (this.mainPage && !!this.source.role) {
          return bbn.fn.getRow(this.mainPage.optionsRoles, 'value', this.source.role);
        }
        return false;
      },
      isCollapsed(){
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
      money: bbn.fn.money,
      getMenuSource(){
        if (!this.editable || this.isDeleted) {
          return [];
        }
        let menu = [{
          text: bbn._('Open in new window'),
          icon: 'nf nf-md-open_in_new',
          action: this.seeTask
        }, {
          text: this.isCollapsed ? bbn._('Expand') : bbn._('Collapse'),
          icon: this.isCollapsed ? 'nf nf-md-arrow_expand' : 'nf nf-md-arrow_collapse',
          action: this.toggleCollapsed
        }];
        if (this.canChange) {
          menu.push({
            text: bbn._('Edit title'),
            icon: 'nf nf-md-format_title',
            action: this.editTitle
          }, {
            text: bbn._('Priority'),
            icon: 'nf nf-md-numeric',
            items: this.getPriorityMenuSource()
          });
        }
        let status = this.getStatusMenuSource();
        if (status.length) {
          menu.push({
            text: bbn._('Status'),
            icon: 'nf nf-md-play_pause',
            items: status
          });
        }
        if (this.canChange) {
          let rolesItems = [{
            text: bbn._('Add or remove managers'),
            icon: 'nf nf-md-account_tie',
            action: () => {
              this.manageRole('managers');
            },
            backgroundColor: this.mainPage.getRoleBgColor('managers'),
            color: this.mainPage.getRoleColor('managers')
          }, {
            text: bbn._('Add or remove workers'),
            icon: 'nf nf-md-account_hard_hat',
            action: () => {
              this.manageRole('workers');
            },
            backgroundColor: this.mainPage.getRoleBgColor('workers'),
            color: this.mainPage.getRoleColor('workers')
          }, {
            text: bbn._('Add or remove viewers'),
            icon: 'nf nf-md-account_eye',
            action: () => {
              this.manageRole('viewers');
            },
            backgroundColor: this.mainPage.getRoleBgColor('viewers'),
            color: this.mainPage.getRoleColor('viewers')
          }];
          if (this.canChangeDecider) {
            rolesItems.push({
              text: bbn._('Add or remove deciders'),
              icon: 'nf nf-md-account_cash',
              action: () => {
                this.manageRole('deciders');
              },
              backgroundColor: this.mainPage.getRoleBgColor('deciders'),
              color: this.mainPage.getRoleColor('deciders')
            });
          }
          menu.push({
            text: bbn._('Roles'),
            icon: 'nf nf-fa-users',
            items: rolesItems
          });
        }
        let budget = this.getBudgetMenuSource();
        if (budget.length > 1) {
          budget = [{
            text: bbn._('Budget'),
            icon: 'nf nf-fa-money',
            items: budget
          }];
        }
        menu.push(...budget);
        if (this.source.num_notes) {
          menu.push({
            text: bbn._('Open notes'),
            icon: 'nf nf-md-comment_multiple_outline',
            action: this.openNotes
          });
        }
        if (this.canChange && this.removeParent) {
          menu.push({
            text: bbn._('Remove as subtask'),
            icon: 'nf nf-md-playlist_remove',
            action: this.removeAsSubtask
          });
        }
        return menu;
      },
      getStatusMenuSource(){
        if (!this.editable) {
          return [];
        }
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
          if (this.canCancel) {
            menu.push({
              text: bbn._('Cancel'),
              icon: 'nf nf-fa-remove',
              action: this.cancel,
              backgroundColor: this.mainPage.getStatusBgColor('canceled'),
              color: this.mainPage.getStatusColor('canceled')
            });
          }
        }
        if (this.canRemoveTask) {
          menu.push({
            text: bbn._('Delete'),
            icon: 'nf nf-fa-trash',
            action: this.removeTask,
            backgroundColor: this.mainPage.getStatusBgColor('deleted'),
            color: this.mainPage.getStatusColor('deleted')
          });
        }
        return menu;
      },
      getBudgetMenuSource(){
        let menu = [];
        if ((this.isAdmin || this.isAccountManager)
          && !this.isCanceled
          && (!this.isClosed || !!this.source.price)
          && !this.source.children_price
          && !this.source.parent_has_price
        ) {
          if (!!this.source.price) {
            menu.push({
              text: bbn._('Edit budget'),
              icon: 'nf nf-fa-edit',
              action: this.editBudget
            }, {
              text: bbn._('Remove budget'),
              icon: 'nf nf-md-delete_empty',
              action: this.removeBudget
            });
          }
          else {
            menu.push({
              text: bbn._('Add budget'),
              icon: 'nf nf-fa-money',
              action: this.editBudget
            });
          }
        }
        return menu;
      },
      getPriorityMenuSource(){
        if (!this.editable) {
          return [];
        }
        let menu = [];
        if (this.canChange) {
          bbn.fn.each(this.mainPage.priorities, p => {
            if (p.value !== this.source.priority) {
              menu.push({
                text: p.text,
                action: () => {
                  this.update('priority', p.value);
                },
                backgroundColor: p.backgroundColor,
                color: p.color,
                cls: 'bbn-c'
              });
            }
          });
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
            label: bbn._('Select user(s)'),
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
          label: false,
          closable: false,
          width: bbn.fn.isMobile() ? '95%' : 600,
          height: bbn.fn.isMobile() ? '95%' : 200,
          component: 'appui-task-item-title',
          source: this.source
        });
      },
      openDescription(){
        this.getPopup({
          label: false,
          closable: false,
          width: bbn.fn.isMobile() ? '95%' : '90%',
          height: bbn.fn.isMobile() ? '95%' : '90%',
          component: 'appui-task-item-description',
          source: this.source
        });
      },
      openNotes(){
        if (!this.isDeleted) {
          this.getPopup({
            label: false,
            closable: false,
            width: bbn.fn.isMobile() ? '95%' : '90%',
            height: bbn.fn.isMobile() ? '95%' : '90%',
            component: 'appui-task-item-notes',
            source: this.source
          });
        }
      },
      editBudget(){
        this.getPopup({
          label: bbn._('Set the price'),
          width: 200,
          component: 'appui-task-item-budget',
          source: this.source
        });
      },
      removeBudget(){
        if ((this.isAdmin || this.isAccountManager)
          && !this.isClosed
          && !bbn.fn.isNull(this.source.price)
        ){
          this.confirm(bbn._('Are you sure you want to remove the price?'), () => {
            this.update('price', null);
          });
        }
      },
      toggleSubtasks(){
        if (!this.editable) {
          this.showSubtasks = false;
          return;
        }
        this.showSubtasks = !!this.source.num_children && !this.showSubtasks;
      },
      toggleCollapsed(){
        this.collapsed = !this.collapsed;
      },
      removeAsSubtask(){
        if (this.canChange && this.removeParent && !!this.source.id_parent) {
          this.post(this.mainPage.root + 'actions/task/subtask/remove', {
            id: this.source.id
          }, d => {
            if (d.success) {
              if (d.children !== undefined) {
                let task = appui.getRegistered('appui-task-' + this.source.id_parent, true);
                let item = this.closest('appui-task-item');
                if (task) {
                  if (task.source.children !== undefined) {
                    task.source.children.splice(0, task.source.children.length, ...d.children);
                    task.source.num_children = d.children.length;
                    let widget = task.findByKey('subtasks', 'bbn-widget');
                    if (widget) {
                      this.$nextTick(() => {
                        widget.reload();
                      })
                    }
                  }
                }
                else if (!!item
                  && (item.source.id === this.source.id_parent)
                  && (item.source.children !== undefined)
                ) {
                  item.source.children.splice(0, item.source.children.length, ...d.children);
                  item.source.num_children = d.children.length;
                }
              }
              appui.success();
            }
          })
        }
      }
    },
    mounted(){
      this.$nextTick(() => {
        let desc = this.getRef('description');
        if (desc?.clientHeight >= 600) {
          this.showOpenContent = true;
        }
      });
    }
  }
})();