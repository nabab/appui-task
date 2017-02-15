// Javascript Document
// Javascript Document
var $tree,
    treeDS = new kendo.data.HierarchicalDataSource({
      filterable: true,
      transport: {
        read: function(e){
          bbn.fn.post(data.root + "tree/" + (e.data.id ? e.data.id : data.cat), function(d){
            $.each(d.data, function(i, v){
              d.data[i].text = v.text.toString();
              d.data[i].is_parent = v.num_children > 0 ? true : false;
            });
            e.success(d);
          });
        }
      },
      schema: {
        data: "data",
        model: {
          id: "id",
          hasChildren: "is_parent",
          fields: {
            type: {type: "string"},
            text: {type: "string"},
            code: {type: "string"},
            is_parent: {type: "bool"}
          }
        }
      }
    }),
    treeCfg = {
      dataSource: treeDS,
      template: function (e) {
        var st = '<i class="fa fa-' + (e.item.icon ? e.item.icon : 'cog') + '"></i> ' + e.item.text;
        return st;
      }
    };
if ( data.is_admin ){
  $.extend(treeCfg, {
    dragAndDrop: true,
    drag: function(e){
      var dd = false,
          ds = this.dataItem(e.sourceNode),
          ok = 1;
      if ( e.dropTarget === undefined ){
        ok = false;
      }
      dd = this.dataItem(e.dropTarget);
      if ( ok ){
        if ( (dd.id_parent !== 0) && !dd.cfg.allow_children ){
          ok = false;
        }
        else if ( ds.id === dd.id ){
          ok = false;
        }
        else if ( !dd.cfg.sortable && (ds.id_parent === dd.id) ){
          ok = false;
        }
      }
      if ( !ok ){
        if ( e.setStatusClass !== undefined ){
          e.setStatusClass("k-denied");
        }
        if ( e.setValid !== undefined ){
          e.setValid(false);
        }
      }
    },
    drop: function(e){
      if ( e.valid && confirm(data.lng.confirm_move) ){
        var tree = this,
            dd = this.dataItem(e.destinationNode),
            ds = this.dataItem(e.sourceNode),
            prev = $(e.sourceNode).prev(),
            parent = $(e.sourceNode).parent();
        bbn.fn.log(e);
        bbn.fn.post(data.root + 'actions/move', {
          dest: dd.id,
          src: ds.id
        }, function(d){
          if ( !d.res ){
            e.setValid(false);
            e.preventDefault();
            bbn.fn.alert(data.lng.problem_while_moving, bbn.lng.error, 400);
            tree.dataSource.read();
          }
        });
      }
      else{
        e.preventDefault();
      }
    }
  });
}
$("div.bbn_options_tree", ele).kendoTreeView(treeCfg);
