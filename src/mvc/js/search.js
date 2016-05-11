// Javascript Document
$("#appui_task_splitter", ele).kendoSplitter({
  orientation: "vertical",
  panes: [
    { collapsible: false, resizable: false, size: "50px", scrollable: false },
    { collapsible: false, resizable: false, scrollable: false }
  ]
});

var ds = new kendo.data.TreeListDataSource({
      sort: [{
        field: "priority",
        dir: "asc"
      }],
      transport: {
        read: function(e){
          var myData = {
                selection: $("input[name=selection]").val() || 'mine'
              },
        			v = $(".appui-task-search-container input.appui-lg", ele).val();
          if ( v ){
            myData.search = v;
          }
          appui.fn.post(data.root + 'treelist', myData, function(d){
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
    }),
    gant_container = $("div.appui-task-gantt", ele);

kendo.bind(ele, {
  change_selection: function(){
    ds.read();
  }
});
gant_container.kendoTreeList({
  autoBind: false,
  sortable: true,
  dataSource: ds,
  columnMenu: true,
  dataBound: function(e){
    e.sender.element.find("tbody tr").each(function(){
      var v = e.sender.dataItem(this);
      $(this).find("td:eq(1)").css({
        backgroundColor: appui.tasks.priority_colors[v.priority-1],
        color: v.priority > 6 ? '#666' : '#EEE'
      })
    });
  },
  columns: [
    {
      field: "title",
      title: "Title",
      expandable: true,
    }, {
      field: "priority",
      title: "!",
      attributes: {
        style: "text-align: center; font-weight: bold; border-top: 1px solid #FFF"
      },
      width: 60
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
      field: "num_notes",
      title: "#",
      width: 50
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
      hidden: true,
      template: function(e){
        var t = moment(e.first);
        return t.fromNow();
      }
    }, {
      field: "last",
      title: "Last",
      width: 100,
      hidden: true,
      template: function(e){
        var t = moment(e.last);
        return t.format("DD MMM YY");
      }
    }, {
      field: "target_date",
      title: "Deadline",
      width: 100,
      template: function(e){
        var t = moment(e.last);
        return t.format("DD MMM YY");
      }
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

$(ele).closest(".ui-tabNav").tabNav("addCallback", function(cont){
  var $input = $("input[name=title]:first", cont);
  if ( !$input.val() ){
    ds.read();
  }
}, ele);

var timer;
$(".appui-task-search-container input.appui-lg", ele).keyup(function(e){
  clearTimeout(timer);
  timer = setTimeout(function(){
    ds.read();
  }, 1000);
});
$(".appui-task-search-container button", ele).click(function(){
  var $input = $(".appui-task-search-container input.appui-lg", ele),
      v = $input.val();
  if ( v.length ){
    appui.tasks.formNew(v);
    $input.val("");
    ds.read();
  }
});