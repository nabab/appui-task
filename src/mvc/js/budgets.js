// Javascript Document

(() => {
  return {
    data() {
      return {
        root: appui.plugins['appui-task'] + '/'
      }
    },
    methods: {
      renderTitle(row) {
        return '<a href="' + this.root + 'page/task/' + row.id + '">' + row.title + '</a>';
      },
      renderPrice(row) {
        let st = '<span class="bbn-b ';
        if (row.accepter) {
          st += 'bbn-green" title="' + this.getAcceptation(row) + '"';
        }
        else {
          st += 'bbn-red"';
        }

        st += '>' + row.price + ' &euro; ';
        if (row.accepter) {
          st += '<i class="nf nf-fa-info_circle"></i>';
        }

        st += '</span>';

        return st;
      },
      getAcceptation(row) {
        if (row.accepter) {
          return bbn._(
            "%s accepted on %s",
            appui.app.getUserName(row.accepter),
            bbn.fn.fdate(row.accept_date, 's')
          )
        }

        return '-';
      }
    }
  }
})();
