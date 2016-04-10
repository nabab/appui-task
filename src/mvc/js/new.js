// Javascript Document
var textarea = $("textarea", ele),
	  cont_textarea = textarea.parent(),
    old_type = 'text';

kendo.bind(ele, {
  change_comment_type: function(){
    var type = $("select.comment_type", ele).data("kendoDropDownList").value(),
        textarea_st = '<textarea class="k-textbox" name="comment"></textarea>',
        text = textarea.val(),
        val;

    if ( textarea.is(".ui-codemirror") ){
      val = textarea.val();
    }
    else if ( textarea.data("kendoEditor") ){
      val = textarea.data("kendoEditor").value();
    }
    else{
      val = textarea.val();
    }

    cont_textarea.html(textarea_st);
    textarea = $("textarea", ele);
    if ( val ){
      textarea.val(val);
    }

    switch ( type ){
      case "text":
        break;

      case "html":
        textarea.kendoEditor();
        break;

      case "gfm":
        textarea.codemirror({
          theme: "default",
          height: 200,
          mode: "gfm"
        });
        break;

      case "php":
        textarea.codemirror({
          height: 200,
          mode: "php"
        });
        break;

      case "js":
        textarea.codemirror({
          height: 200,
          mode: "js"
        });
        break;

      case "css":
        textarea.codemirror({
          height: 200,
          mode: "less"
        });
        break;
    }
  },
  users: [],
  ref: data.ref,
  roles: data.roles,
  change_role: function(){
    var role = $("input[name=role]", ele).data("kendoDropDownList").value();
    if ( role ){
      appui.fn.window("usergroup/picker", {picker: "#bbn_pm_form_container input[name=id_user]"}, 350, 700);
    }
  },
  add_user: function(){
    var dataItem = this,
        id_user = $("input[name=id_user]", ele).val(),
        rolePicker = $("input[name=role]", ele).data("kendoDropDownList"),
        role = rolePicker.value(),
        frole = appui.fn.get_field(dataItem.roles, "value", role, "text");
    if ( frole && ($.inArray(dataItem.users, id_user) === -1) ){
      appui.fn.post("usergroup/get_user", {id_user: id_user}, function(d){
        if ( d.res ){
          dataItem.users.push(id_user);
          rolePicker.value("");
          $("div.appui-task-roles-container").append(
            $('<div class="appui-nl"/>').append(
              /** @todo Change this! */
              '<span>' + apst.fnom(d.res)  + ' (' + frole + ')</span> &nbsp; ',
              $('<i class="fa fa-times appui-p"/>').click(function(e){
                if ( (id_user !== appui.env.userId) || confirm(data.confirm_delete) ){
                var idx = $.inArray(dataItem.users, id_user);
                if ( idx > -1 ){
                  dataItem.users.splice(idx, 1);
                }
                $(this).closest("div.appui-nl").remove();
                }
              })
            )
          );
        }
      });
    }
  },
  insert: function(){
    appui.fn.log(appui.fn.formdata(ele));
  }
});

var $tree,
    $li,
    treeDS = new kendo.data.HierarchicalDataSource({
      data: data.groups,
      schema: {
        model: {
          id: "id",
          hasChildren: "is_parent",
          children: "items",
          fields: {
            text: {type: "string"},
            is_parent: {type: "bool"}
          }
        }
      }
    }),
    dragCfg = {
      helper: "clone",
      containment: $("div.bbn_pm_form_container", ele),
      scroll: false,
    };


$tree = $("div.appui-task-usertree", ele).kendoTreeView({
  dataSource: treeDS,
  expand: function(e){
    appui.fn.log(e.node);
    setTimeout(function(){
      $(e.node).find("li.k-item").each(function(){
        var $$ = $(this);
        if ( !$$.hasClass("ui-draggable") ){
          $$.draggable(dragCfg);
        }
      });
    }, 300);
  },
  select: function (e) {
    if ( data.picker ){
      var r = this.dataItem(e.node);
      $(data.picker).val(r.id).trigger("change");
      appui.fn.closeAlert();
    }
  },
  template: function (e) {
    return '<i class="fa fa-' + (e.item.is_parent ? 'users' : 'user') + '"> </i> ' + e.item.text;
  }
}).data("kendoTreeView");
$li = $("li.k-item li.k-item", $tree.element);
$("input:first", ele).keyup(function(){
  var v = $(this).val().toLowerCase();
  $li.filter(":hidden").show();
  if ( v.length ){
    $li.filter(function(){
      var txt = appui.fn.removeAccents($(this).find("span:not(.k-icon):first").text().toLowerCase());
      return txt.indexOf(v) === -1;
    }).hide();
  }
  /*
  var data = treeDS.data();
  data.forEach(function(a){
    appui.fn.log(a, this);
    a.filter({field: "text", operator: "contains", value: $(this).val()});
  })
  appui.fn.log(treeDS, treeDS.data());
  treeDS.filter({field: "text", operator: "contains", value: $(this).val()});
  Porca putana!
  */
});

$li.draggable(dragCfg);

$("div.appui-task-assigned", ele).droppable({
  accept: "*",
  hoverClass: "selected",
  activeClass: "active",
  drop: function(e, ui){
    var $ul = $(this).find("ul"),
        dataItem = $tree.dataItem(ui.draggable).toJSON(),
        $input = $(this).find("input"),
        v = $input.val(),
        vals = v ? JSON.parse(v) : [],
        id = dataItem.id;
    vals.push(id);
    $input.val(JSON.stringify(vals));
    ui.draggable.hide();
    $ul.append('<li class="k-item">' + ui.helper.html() + '</li>');
    $ul.find("li.k-item:last span").append(
      '&nbsp;',
      $('<i class="fa fa-times appui-p"/>').data("id", id).click(function(){
        ui.draggable.show();
        v = $input.val();
        vals = v ? JSON.parse(v) : [];
        var idx = $.inArray(id, vals);
        if ( idx > -1 ){
          vals.splice(idx, 1);
          $input.val(JSON.stringify(vals));
        }
        $(this).closest("li").remove();
        appui.fn.log(idx, id);
      })
    )
  }
});

var ddTree = $("input[name=type]", ele).kendoDropDownTreeView({
  optionLabel: appui.lng.choose,
  treeview: {
    select: function(e){
      ddTree.element.val(e.sender.dataItem(e.node).id).change();
      appui.fn.log(ddTree.element, e.sender.dataItem(e.node));
    },
    dataTextField: "text",
    dataValueField: "id",
    dataSource: new kendo.data.HierarchicalDataSource({
      data: data.categories,
      schema: {
        model: {
          id: "id",
          hasChildren: "is_parent",
          children: "items",
          fields: {
            text: {type: "string"},
            is_parent: {type: "bool"}
          }
        }
      }
    })
  }
}).data("kendoDropDownTreeView");

var uploader = $("input[name=file]");
uploader.kendoUpload({
  async: {
    saveUrl: "file/save/" + $("input[name=ref]", ele).val(),
    removeUrl: "file/delete/" + $("input[name=ref]", ele).val(),
  },

  // @todo Voir la taille
  files: /*d.fichiers ? $.map(d.fichiers, function(a, i){
    return {name: a, size: 1000, ext: a.substr(x.lastIndexOf("."))};
  }) :*/ [],

  upload: function(e){
    var st = uploader.val(),
        v = st ? JSON.parse(st) : [];
    appui.fn.log(e.files);
    if ( v.length > 0 ){
      $.each(e.files, function (i, f){
        if ( $.inArray(f.rawFile.name, v) > -1 ){
          e.preventDefault();
          appui.fn.alert(data.lng.file_exists);
          return false;
        }
      });
    }
  },
  success: function(e){
    if ( e.response && e.files && (e.response.success === 1) ){
      var idx,
          st = uploader.val(),
          fichiers = st ? JSON.parse(st) : [];
      if ( (e.operation === 'upload') && e.response.fichier ){
        $.each(e.files, function(i, f){
          fichiers.push(f.rawFile.name);
        });
      }
      else if ( (e.operation === 'remove') ){
        $.each(e.files, function(i, f){
          if ( (idx = $.inArray(f.rawFile.name, fichiers)) > -1 ){
            fichiers.splice(idx, 1);
          }
        });
      }
      inp.val(JSON.stringify(fichiers));
    }
    else{
      appui.fn.alert(data.lng.problem_file);
    }
  },
  error:function(e){
    appui.fn.alert(data.lng.error_uploading)
  }
});

$("button.appui-task-link-button", ele).mousedown(function(){
  var v = $(this).prev("input").val();
  appui.fn.post(data.root + "link_preview", {url: v}, function(d){
    if ( d.res ){
       $("div.bbn-task-files-container", ele).append(
         $('<div class="appui-nl"/>').append(
           '<img class="appui-block" src="' + d.res.pictures[0] + '" style="width: 200px; height: auto">',
           '<div class="appui-spacer"/>',
           $('<div class="appui-block"/>').append(
             '<h3>' + d.res.title + '</h3>',
             '<p>' + d.res.desc + '</p>'
           )
         )
       );
    }
  });
});