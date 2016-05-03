// Javascript Document
var ds = new kendo.data.TreeListDataSource({
  sort: {
    field: "priority"
  },
  serverSorting: false,
  transport: {
    read: function(e){
      appui.fn.post(data.root + 'treelist', e.data ? e.data : {}, function(d){
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
    model: {
      id: "id",
      parentId: "id_parent",
      fields: {
        id: {type: "number", nullable: false},
        id_parent: {type: "number", nullable: true},
        is_parent: {type: "boolean"},
        first: {type: "date"},
        last: {type: "date"},
        title: {type: "string"},
        priority: {type: "number"},
        state: {type: "string"},
        type: {type: "string"},
        id_user: {type: "string"},
      }
    }
  }
});
$("div.appui-task-gantt", ele).kendoTreeList({
  pageable: {
    refresh: true
  },
  sortable: true,
  dataBound: function(e){
    $(e.sender.element).find("tbody tr[class*='appui-task-pr'] td:first-child").each(function(){
      var $$ = $(this);
      for ( var i = 1; i <= 10; i++ ){
        $$.removeClass("appui-task-pr" + i);
      }
    });
    $(e.sender.element).find("tbody tr td:first-child").each(function(){
      var dataItem = e.sender.dataItem(this);
      $(this).addClass("appui-task-pr" + dataItem.get("priority"));
      appui.fn.log("Adding class");
    })
  },
  dataSource: ds,
  columns: [
    {
      field: "priority",
      title: "Priority",
      width: 100,
      expandable: true
    }, {
      field: "id_parent",
      hidden: true
    }, {
      field: "is_parent",
      hidden: true
    }, {
      field: "type",
      title: "Type",
      width: 150
    }, {
      field: "state",
      title: "State",
      width: 120
    }, {
      field: "duration",
      title: "Duration",
      width: 100,
      template: function(e){
        if ( !e.duration ){
          return 'Inconnue';
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
      field: "first",
      title: "Start",
      width: 100,
      template: function(e){
        var t = moment(e.first);
        return t.fromNow();
      }
    }, {
      field: "last",
      title: "Last",
      width: 100,
      template: function(e){
        var t = moment(e.last);
        return t.format("DD MMM YY");
      }
    }, {
      field: "title",
      title: "Title",
    }, {
      field: "id",
      title: " ",
      width: 50,
      template: function(e){
        return '<a href="' + data.root + 'tasks/display_' + e.id + '" title="' + data.lng.see_task + '"><button class="k-button"><i class="fa fa-eye"> </i></button></a>';
      }
    }
  ]
});
