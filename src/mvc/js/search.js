// Javascript Document
$("#appui_task_splitter", ele).kendoSplitter({
  orientation: "vertical",
  panes: [
    { collapsible: false, resizable: false, size: "50px" },
    { collapsible: false, resizable: false }
  ]
});

var grid = $(".appui-task-results-container > div", ele).kendoGrid({
      autoBind: false,
      scrollable: true,
      detailInit: function(e){
        appui.fn.log(e);
      },
      columns: [
        {
          title: "ID",
          field: "id",
          hidden: true,
        }, {
          title: "Title",
          field: "title",
        }, {
          title: "Created by",
          field: "userf",
          width: 200
        }, {
          title: "Date",
          field: "creationf",
          width: 100
        }, {
          title: " ",
          field: "id",
          width: 50,
          template: function(e){
            return '<a href="' + data.root + 'tasks/' + e.id + '"><button class="k-button"><i class="fa fa-eye"> </i></button></a>';
          }
        }
      ]
    }).hide(),
    $grid = grid.data("kendoGrid");

appui.fn.log($grid);
$(".appui-task-search-container input.appui-lg", ele).keyup(function(e){
  var v = $(this).val();
  if ( v.length > 1 ){
    appui.fn.post(data.root + "search", {search: v, appui_baseURL: data.root}, function(d){
      if ( d.data.rows ){
        for ( var i = 0; i < d.data.length; i++ ){
          
        }
        grid.show();
        $grid.dataSource.data(d.data.rows);
        //kendo.bind($ul, d.data);
      }
      else{
        grid.show();
        $grid.dataSource.data([]);
      }
    });
  }
});
