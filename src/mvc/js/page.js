// Javascript Document
(() => {
  return {
    name: 'appui-task',
    data(){
      return {
        root: appui.plugins['appui-task'] + '/',
        priorities: Array.from({length: 10}, (v, n) => {
          return {
            text: n + 1,
            value: n + 1,
            class: 'appui-task-pr' + (n + 1),
            backgroundColor: 'var(--appui-task-pr' + (n + 1) + ')',
            color: 'white'
          }
        }),
        priority_colors: [
          '#F00',
          '#F40',
          '#F90',
          '#FC0',
          '#9B3',
          '#7A4',
          '#5A5',
          '#396',
          '#284',
          '#063'
        ]
      };
    },
    computed: {
      fullCategories(){
        let res = [];
        const getItem = (cat, group) => {
          let items = cat.items || false;
          delete cat.items;
          cat.value = cat.id;
          cat.group = group || (items ? cat.text : '');
          res.push(cat);
          if ( items ){
           bbn.fn.each(items, (c, i) => {
              getItem(c, cat.text);
            });
          }
        };
        if ( this.source.categories ){
          bbn.fn.each(bbn.fn.extend(true, {}, this.source.categories), cat => {
            getItem(cat);
          });
        }
        return res;
      },
      groups(){
        let users = bbn.fn.extend(true, [], appui.app.users);
        return bbn.fn.map(bbn.fn.extend(true, [], this.source.groups), v => {
          v.text = v.nom || v.text
          v.expanded = false;
          v.items = bbn.fn.map(
            bbn.fn.filter(users, user => {
              return user.active && (user.id_group === v.id);
            }),
            user => {
              user.id = user.value;
              user.icon = 'nf nf-fa-user';
              return user;
            }
          );
          if (v.is_parent) {
            v.icon = 'nf nf-fa-users';
          }
          return v;
        });
      },
      states(){
        return this.source.states;
      },
      optionsStates(){
        return this.source.options.states;
      },
      optionsRoles(){
        return this.source.options.roles;
      },
      privileges(){
        return this.source.privileges;
      }
    },
    methods: {
      isMobile: bbn.fn.isMobile,
      userName(id){
        return bbn.fn.getField(appui.app.users, 'text', 'value', id);
      },
      userGroup(id){
        return bbn.fn.getField(appui.app.users, 'id_group', 'value', id);
      },
      userAvatar(id){
        const av = bbn.fn.getField(appui.app.users, 'avatar', 'value', id);
        return av ? av : bbn.var.defaultAvatar;
      },
      userAvatarImg(id){
        const av = this.userAvatar(id),
              name = this.userName(id);
        return '<span class="appui-avatar"><img src="' + av + '" alt="' + name + '" title="' + name + '"></span>';
      },
      userFull(id){
        const user = bbn.fn.getRow(appui.app.users, 'value', id);
        return '<span class="appui-avatar"><img src="' + user.avatar + '" alt="' + user.text + '"> ' + user.text + '</span>';
      },
      isYou(id){
        return id === appui.app.user.id;
      },
      formatDate(d){
        return dayjs(d).format('DD/MM/YYYY HH:mm');
      },
      getRoleColor(code){
        if (this.optionsRoles) {
          return bbn.fn.getField(this.optionsRoles, 'color', {code: code});
        }
        return '';
      },
      getRoleBgColor(code){
        if (this.optionsRoles) {
          return bbn.fn.getField(this.optionsRoles, 'backgroundColor', {code: code});
        }
        return '';
      },
      getStatusColor(code){
        if (this.optionsStates) {
          return bbn.fn.getField(this.optionsStates, 'color', {code: code});
        }
        return '';
      },
      getStatusBgColor(code){
        if (this.optionsStates) {
          return bbn.fn.getField(this.optionsStates, 'backgroundColor', {code: code});
        }
        return '';
      },
      getStatusCode(idStatus){
        if (this.optionsStates) {
          return bbn.fn.getField(this.optionsStates, 'code', {value: idStatus});
        }
        return '';
      }
    }
  };
})();
