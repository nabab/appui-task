// Javascript Document
// Splitter between searchbar and list
$(".appui-task-splitter", ele).kendoSplitter({
  orientation: "vertical",
  panes: [
    { collapsible: false, resizable: false, size: "50px", scrollable: false },
    { collapsible: false, resizable: false, scrollable: false }
  ]
});

var operators = kendo.ui.FilterCell.fn.options.operators,
    gant_container = $("div.appui-task-gantt", ele),
    ds = new kendo.data.DataSource({
      serverFiltering: true,
      serverSorting: true,
      serverPaging: true,
      pageSize: 25,
      sort: [{
        field: "priority",
        dir: "asc"
      }],
      filter: {
        filters: [
          {field: "state", operator: "eq", value: 1119180276},
          {field: "state", operator: "eq", value: 510149757},
          {field: "state", operator: "eq", value: 1349638282}
        ],
        logic: "or"
      },
      transport: {
        read: function(e){
          bbn.fn.log(e);
          if ( e.data && e.data.filter && e.data.filter.filters ){
            for ( var i = 0; i < e.data.filter.filters.length; i++ ){
              if ( e.data.filter.filters[i].field ){
                if ( ds.options.schema.model.fields[e.data.filter.filters[i].field].type === "date" ){
                  e.data.filter.filters[i].value = kendo.parseDate(e.data.filter.filters[i].value);
                  e.data.filter.filters[i].value = kendo.toString(e.data.filter.filters[i].value, 'yyyy-MM-dd HH:mm:ss');
                }
              }
            }
          }
          var myData = {
                selection: $("select[name=selection]", ele).data("kendoDropDownList").value()
              },
              v = $(".appui-task-search-container input[name=title]", ele).val();
          if ( v ){
            myData.title = v;
          }
          bbn.fn.post(data.root + 'treelist', $.extend(myData, e.data), function(d){
            if ( d && d.tasks ){
              e.success(d.tasks);
            }
            else{
              e.error();
            }
          });
        }
      },
      schema: {
        data: "data",
        total: "total",
        model: {
          id: "id",
          fields: {
            id: {type: "number", nullable: false},
            id_parent: {type: "number", nullable: true},
            is_parent: {type: "boolean"},
            creation_date: {type: "date"},
            last_action: {type: "date"},
            title: {type: "string"},
            priority: {type: "number", nullable: false},
            state: {type: "number"},
            num_notes: {type: "number"},
            deadline: {type: "date", nullable: true},
            role: {type: "number"},
            type: {type: "number"},
            reference: {type: "string"},
            id_user: {type: "number"},
          }
        }
      }
    });

kendo.bind(ele, {
  change_selection: function(){
    ds.read();
  },
  create_task: function(){
    var $input = $("input[name=title]", ele),
        v = $input.val();
    if ( v.length ){
      bbn.tasks.formNew(v);
      $input.val("");
      ds.read();
    }
  }
});
gant_container.kendoGrid({
  autoBind: false,
  sortable: true,
  pageable: {
    pageSize: 25,
    refresh: true
  },
  resizable: true,
  filterable: {
    extra: false
  },
  dataSource: ds,
  columnMenu: true,
  dataBound: function(e){
    e.sender.element.find("tbody tr").each(function(){
      var v = e.sender.dataItem(this);
      //bbn.fn.log(e);
      $(this).find("td").css({backgroundColor: "transparent"}).eq(bbn.fn.search(e.sender.columns, "field", "priority")).css({
        backgroundColor: "#" + bbn.tasks.priority_colors[v.priority-1]
      })
    });
  },
  columns: [
    {
      field: "id_user",
      title: bbn.tasks.lng.user,
      template: function(e){
        return apst.userAvatarImg(e.id_user);
      },
      width: 50,
      values: appui.apst.users
    }, {
      field: "priority",
      title: bbn.tasks.lng.priority,
      width: 60,
      attributes: {
        style: "text-align: center; font-weight: bold; border-top: 1px solid white; color: white"
      },
      filterable: {
        operators:{
          number: {
            eq: operators.number.eq,
            gt: operators.number.gt,
            lt: operators.number.lt
          }
        }
      },
    }, {
      field: "num_notes",
      title: "#Notes",
      filterable: {
        operators:{
          number: {
            eq: operators.number.eq,
            gt: operators.number.gt,
            lt: operators.number.lt
          }
        }
      },
      width: 50
    }, {
      field: "state",
      filterable: {
        multi: true
      },
      sortable: false,
      title: bbn.tasks.lng.state,
      width: 50,
      values: bbn.tasks.options.states,
      encoded: false,
      template: function(e){
        var icon,
            color;
        if ( e.state === bbn.tasks.states.opened ){
          icon = 'clock-o';
          color = 'white';
        }
        else if ( e.state === bbn.tasks.states.pending ){
          icon = 'clock-o';
          color = 'red';
        }
        else if ( e.state === bbn.tasks.states.ongoing ){
          icon = 'play';
          color = 'blue';
        }
        else if ( e.state === bbn.tasks.states.closed ){
          icon = 'check';
          color = 'green';
        }
        else if ( e.state === bbn.tasks.states.holding ){
          icon = 'pause';
          color = 'grey';
        }
        return '<i class="appui-lg fa fa-' + icon + '" style="color: ' + color + '" style="" title="' + bbn.fn.get_field(bbn.tasks.options.states, "value", e.state, "text") + '"> </i>';
      }
    }, {
      field: "last_action",
      title: bbn.tasks.lng.last,
      width: 100,
      filterable: {
        operators:{
          date: {
            eq: operators.date.eq,
            gt: operators.date.gt,
            lt: operators.date.lt
          }
        }
      },
      template: function(e){
        var t = moment(e.last_action);
        return t.calendar();
      }
    }, {
      field: "role",
      filterable: {
        multi: true
      },
      sortable: false,
      title: bbn.tasks.lng.role,
      width: 80,
      values: bbn.tasks.options.roles,
      template: function(e){
        return bbn.fn.get_field(bbn.tasks.options.roles, "value", e.role, "text") || '-';
      }
    }, {
      field: "type",
      filterable: {
        multi: true
      },
      sortable: false,
      title: bbn.tasks.lng.type,
      attributes: {
        style: "max-width: 300px",
      },
      width: 150,
      values: bbn.tasks.options.cats,
      template: function(e){
        return bbn.fn.get_field(bbn.tasks.options.cats, "value", e.type, "text");
      }
    }, {
      field: "duration",
      title: bbn.tasks.lng.duration,
      width: 70,
      template: function(e){
        var start = moment(e.creation_date),
            end = moment(e.last_action);
        if ( e.state === bbn.tasks.states.closed ){
          end = moment();
        }
        return end.from(start, true);
        if ( !e.duration ){
          return bbn.tasks.lng.inconnue;
        }
        if ( e.duration < 3600 ){
          return Math.round(e.duration/60) + ' mn';
        }
        if ( e.duration < (24*3600) ){
          return Math.round(e.duration/3600) + ' h';
        }
        return Math.round(e.duration/(24*3600)) + ' j';
      },
      hidden: true
    }, {
      field: "title",
      title: bbn.tasks.lng.title,
      menu: false,
      expandable: true,
      filterable: false,
    }, {
      field: "reference",
      title: bbn.tasks.lng.reference,
      encoded: false,
      filterable: false,
      hidden: true
    }, {
      field: "creation_date",
      title: bbn.tasks.lng.start,
      width: 100,
      filterable: {
        operators:{
          date: {
            eq: operators.date.eq,
            gt: operators.date.gt,
            lt: operators.date.lt
          }
        }
      },
      template: function(e){
        var t = moment(e.creation_date);
        return t.calendar();
      }
    }, {
      field: "deadline",
      title: bbn.tasks.lng.dead,
      width: 100,
      encoded: false,
      filterable: {
        operators:{
          date: {
            eq: operators.date.eq,
            gt: operators.date.gt,
            lt: operators.date.lt,
            isnull: operators.date.isnull,
            isnotnull: operators.date.isnotnull
          }
        }
      },
      template: function(e){
        var t = moment(e.deadline),
            now = moment(),
            diff = t.unix() - now.unix(),
            col = 'green',
            d = e.state === bbn.tasks.states.closed ? t.calendar() : t.fromNow();
          ;
        if ( !t.isValid() ){
          return '-';
        }
        if ( diff < 0 ){
          col = 'brown'
        }
        else if ( diff < (3*24*3600) ){
          col = 'red'
        }
        else if ( diff < (7*24*3600) ){
          col = 'orange'
        }
        else if ( diff < (7*24*3600) ){
          col = 'orange'
        }
        return '<strong style="color: ' + col + '">' + d + '</strong>';
      }
    }, {
      field: "id",
      title: " ",
      menu: false,
      filterable: false,
      sortable: false,
      width: 50,
      template: function(e){
        return '<a href="' + data.root + 'tasks/' + e.id + '" title="' + bbn.tasks.lng.see_task + '"><button class="k-button"><i class="fa fa-eye"> </i></button></a>';
      }
    }/*, {
      field: "id_parent",
      hidden: true,
      menu: false,
      filterable: false,
      sortable: false,
    }, {
      field: "is_parent",
      hidden: true,
      menu: false,
      filterable: false,
      sortable: false,
    }*/
  ]
});

$(ele).closest(".ui-tabNav").tabNav("addCallback", function(cont){
  var $input = $("input[name=title]:first", cont);
  if ( !$input.val() ){
    ds.read();
  }
}, ele);

var timer;
$(".appui-task-search-container input[name=title]", ele).keyup(function(e){
  if ( timer ){
    clearTimeout(timer);
  }
  if ( e.key === "Enter" ){
    $(this).closest(".appui-form-full").find("button").click();
    setTimeout(function(){
      bbn.fn.get_popup().find("input[name=title]").focus();
    }, 200);
  }
  else{
    timer = setTimeout(function(){
      ds.read();
    }, 1000);
  }
});
