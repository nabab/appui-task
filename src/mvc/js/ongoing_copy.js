// Javascript Document
var ds = new kendo.data.TreeListDataSource({
  transport: {
    read: function(e){
      appui.fn.log(e);
      appui.fn.post(data.root + 'treelist', e.data ? e.data : {}, function(d){
        if ( d && d.tasks ){
          appui.fn.log("OK");
          //d.tasks.unshift({id:0, is_parent: true, id_parent: null, title: "My tasks"});
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
  sortable: true,
  dataSource: ds,
  columns: [
    {
      field: "priority",
      title: "Priority",
      width: 100,
      expandable: true
    }, {
      field: "id",
      hidden: true
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
    }
  ]
});