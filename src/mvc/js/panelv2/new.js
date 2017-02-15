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
      bbn.fn.window("usergroup/picker", {picker: "#appui_pm_form_container input[name=id_user]"}, 350, 700);
    }
  },
  add_user: function(){
    var dataItem = this,
        id_user = $("input[name=id_user]", ele).val(),
        rolePicker = $("input[name=role]", ele).data("kendoDropDownList"),
        role = rolePicker.value(),
        frole = bbn.fn.get_field(dataItem.roles, "value", role, "text");
    if ( frole && ($.inArray(dataItem.users, id_user) === -1) ){
      bbn.fn.post("usergroup/get_user", {id_user: id_user}, function(d){
        if ( d.res ){
          dataItem.users.push(id_user);
          rolePicker.value("");
          $("div.appui-task-roles-container").append(
            $('<div class="appui-nl"/>').append(
              /** @todo Change this! */
              '<span>' + apst.fnom(d.res)  + ' (' + frole + ')</span> &nbsp; ',
              $('<i class="fa fa-times appui-p"/>').click(function(e){
                var del = function(){
                  var idx = $.inArray(dataItem.users, id_user);
                  if ( idx > -1 ){
                    dataItem.users.splice(idx, 1);
                  }
                  $(this).closest("div.appui-nl").remove();
                };
                if ( id_user !== bbn.env.userId ){
                  del();
                }
                else{
                  bbn.fn.confirm(data.confirm_delete, function(){
                    del();
                  })
                }
              })
            )
          );
        }
      });
    }
  },
  insert: function(){
    bbn.fn.log(bbn.fn.formdata(ele));
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
      containment: $("div.appui_task_form_container", ele),
      scroll: false,
      start: function(e, ui){
        var dataItem = $tree.dataItem(e.target).toJSON();
        if ( dataItem.is_parent && !dataItem.expanded ){
          $tree.expand(e.target);
        }
      }
    };


$tree = $("div.appui-task-usertree", ele).kendoTreeView({
  dataSource: treeDS,
  expand: function(e){
    bbn.fn.log(e.node);
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
      bbn.fn.closePopup();
    }
  },
  template: function (e) {
    return '<i class="fa fa-' + (e.item.is_parent ? 'users' : 'user') + '"> </i> ' + e.item.text;
  }
}).data("kendoTreeView");
$li = $("li.k-item", $tree.element);
$("input:first", ele).keyup(function(){
  var v = $(this).val().toLowerCase();
  $li.filter(":hidden").show();
  if ( v.length ){
    $li.filter(function(){
      var txt = bbn.fn.removeAccents($(this).find("span:not(.k-icon):first").text().toLowerCase());
      return txt.indexOf(v) === -1;
    }).hide();
  }
  /*
  var data = treeDS.data();
  data.forEach(function(a){
    bbn.fn.log(a, this);
    a.filter({field: "text", operator: "contains", value: $(this).val()});
  })
  bbn.fn.log(treeDS, treeDS.data());
  treeDS.filter({field: "text", operator: "contains", value: $(this).val()});
  Porca putana!
  */
});

$li.draggable(dragCfg);

$("div.appui-task-assigned", ele).droppable({
  accept: ".appui-task-usertree li",
  hoverClass: "bbn-dropable-hover",
  activeClass: "bbn-dropable-active",
  drop: function(e, ui){
    var $ul = $(this).find("ul"),
        dataItem = $tree.dataItem(ui.draggable),
        $input = $(this).find("input"),
        v = $input.val(),
        vals = v ? JSON.parse(v) : [],
        items = [],
        $ele;
    bbn.fn.log(dataItem, $tree.dataItem(ui.draggable));
    if ( dataItem.is_parent ){
      if ( dataItem.items && dataItem.items.length ){
        items = dataItem.items;
      }
    }
    else{
      items.push(dataItem);
    }
    $.each(items, function(i, v){
      var id = v.get("id");
      $ele = $tree.findByUid(v.get("uid"));
      if ( $ele.is(":visible") ){
        vals.push(id);
        $ul.append(
          $('<li class="k-item">' + $ele.html() + '</li>').mousedown(function(e){
            e.preventDefault();
            e.stopImmediatePropagation();
            e.stopPropagation();
            return false;
          })
        );
        $ele.hide();
        $ul.find("li.k-item:last span").append(
          '&nbsp;',
          $('<i class="fa fa-times appui-p"/>').data("id", id).data("ele", $ele).click(function(){
            var $$ = $(this);
            $$.data("ele").show();
            v = $input.val();
            vals = v ? JSON.parse(v) : [];
            var idx = $.inArray($$.data("id"), vals);
            if ( idx > -1 ){
              vals.splice(idx, 1);
              $input.val(JSON.stringify(vals));
            }
            $$.closest("li").remove();
            bbn.fn.log(idx, id);
          })
        ).removeEv
      }
    });
    $input.val(JSON.stringify(vals));
  }
});

var ddTree = $("input[name=type]", ele).kendoDropDownTreeView({
  optionLabel: bbn.lng.choose,
  treeview: {
    select: function(e){
      ddTree.element.val(e.sender.dataItem(e.node).id).change();
      bbn.fn.log(ddTree.element, e.sender.dataItem(e.node));
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

var uploadedFiles = [],
    uploadWrapper = $("div.appui-task-upload-wrapper", ele),
    iconClass = function(file){
      if ( file.extension ){
        var ext = file.extension.substr(1),
            cls = "file-o";
        if ( ext === "pdf" ){
          return "file-pdf-o";
        }
        else if ( $.inArray(ext, ["xls", "xlsx", "csv", "ods"]) > -1 ){
          return "file-excel-o";
        }
        else if ( $.inArray(ext, ["rtf", "doc", "docx", "odt"]) > -1 ){
          return "file-word-o";
        }
        else if ( $.inArray(ext, ["svg", "gif", "png", "jpg", "jpeg"]) > -1 ){
          return "file-picture-o";
        }
        else if ( $.inArray(ext, ["zip", "gz", "rar", "7z", "bz2", "xz"]) > -1 ){
          return "file-archive-o";
        }
        return "file-o";
      }
      return false;
    },
    createUpload = function(){
      bbn.fn.log("CREATE", uploadedFiles);
      var uploader = $('<input name="file" type="file"/>');
      uploadWrapper.empty().append(uploader);
      uploader.kendoUpload({
        async: {
          saveUrl: data.root + "upload/" + data.ref,
          removeUrl: data.root + "unupload/" + data.ref
        },
        template: function(e){
          bbn.fn.log("TEM<PLATE", e);
          var size,
              unit;
          bbn.fn.log(e);
          if ( e.size > 5000000 ){
            unit = 'Mb';
            size = Math.floor(e.size / 1024 / 1024).toString();
          }
          else if ( e.size > 1024 ){
            unit = 'Kb';
            size = Math.floor(e.size / 1024).toString();
          }
          else{
            unit = 'b';
            size = e.size;
          }
          if ( e.files && e.files[0] ){
            var st = '<div class="k-progress"><table><tbody><tr><td class="appui-task-link-image">',
                done = false;
            if ( e.files[0].imgs ){
              $.each(e.files[0].imgs, function(i, v){
                if ( v.h96 ){
                  st += '<img src="' + data.root + 'image/tmp/' + data.ref + v.h96 + '">';
                  done = 1;
                  return false;
                }
              });
            }
            if ( !done ){
              st += '<i class="fa fa-' + iconClass(e.files[0]) + '"> </i>';
            }
            st += '</td><td class="appui-task-link-title">' + e.name + '</td>' +
              '<td class="appui-task-link-actions">' +
                '<span class="k-upload-pct">' + kendo.toString(size, 'n0') + ' ' + unit + '</span>' +
                '<button class="k-button k-button-bare k-upload-action" type="button">' +
                '<span class="k-icon k-i-close k-delete" title="' + data.lng.delete + '"></span>' +
                '</button>' +
              '</td></tr></tbody></table></div>';
            return st;
          }
        },

        // @todo Voir la taille
        files: uploadedFiles,
        upload: function(e){
          bbn.fn.log("UPLOAD", e);
          if ( e.files && e.files[0] ){
            var idx = bbn.fn.search(uploadedFiles, "name", e.files[0].name);
            if ( idx > -1 ){
              e.preventDefault();
              bbn.fn.alert(data.lng.file_exists);
              return false;
            }
          }
        },
        success: function(e){
          bbn.fn.log("SUCCESS", e);
          if ( e.response && e.response.files && e.files && (e.response.success === 1) ){
            var file = uploader.val();
            if ( e.operation === 'upload' ){
              $.each(e.response.files, function(i, f){
                if ( f.size ){
                  uploadedFiles.push(f);
                }
              });
              createUpload();
              uploadWrapper.bbn("redraw", true);
            }
            else if ( (e.operation === 'remove') ){
              $.each(e.files, function(i, f){
                if ( (idx = $.inArray(f.rawFile.name, uploadedFiles)) > -1 ){
                  uploadedFiles.splice(idx, 1);
                }
              });
            }
          }
          else{
            bbn.fn.alert(data.lng.problem_file);
          }
        },
        error:function(e){
          bbn.fn.alert(data.lng.error_uploading)
        }
      });
    };
createUpload();


$("input[name=link]", ele).keydown(function(e){
  if ( e.key === "Enter" ){
    e.preventDefault();
    var $input = $(this),
        v = $input.val(),
        $target = $("table.appui-task-links-container", ele),
        $li;
    if ( v.toLowerCase().indexOf("http") !== 0 ){
      v = "http://" + v;
    }
    $target.append(
      '<tr><td class="k-file k-file-progress">' +
      '<div class="k-progress">' +
      '<table><tr>' +
      '<td class="appui-task-link-image"><i class="fa fa-link"> </i></td>' +
      '<td class="appui-task-link-title"><div><strong><a href="' + v + '">' + v + '</a></strong><br></div></td>' +
      '<td class="appui-task-link-actions">' +
        '<span class="k-upload-pct"> </span>' +
        '<button type="button" class="k-button k-button-bare k-upload-action" style="display: inline-block;">' +
          '<span title="' + data.lng.delete + '" class="k-icon k-i-close k-delete"></span>' +
        '</button>' +
      '</tr></table>' +
      '</div>' +
      '</td></tr>'
    );
    $target.bbn("redraw", true);
    $input.val("");
    $li = $target.find("tr:last").parent().closest("tr");
    bbn.fn.post(data.root + "link_preview", {url: v, ref: data.ref}, function(d){
      if ( d.res && d.res.realurl ){
        bbn.fn.log("ok", d.res);
        $li.find("td.k-file").removeClass("k-file-progress").addClass("k-file-success");
        if ( d.res.pictures ){
          $.each(d.res.pictures, function(i, v){
            if ( v.h96 ){
              $li.find("td.appui-task-link-image").html('<img src="pm/image/tmp/' + data.ref + '/' + v.h96 + '">');
              return false;
            }
          });
        }
        var st = '<strong><a href="' + d.res.realurl + '">' +
            ( d.res.title ? d.res.title : d.res.realurl ) +
            '</a></strong><br>';
        if ( d.res.desc ){
          st += d.res.desc;
        }
        $li.find("td.appui-task-link-title div").html(st);
      }
      else{
        $li.find("td.k-file").removeClass("k-file-progress").addClass("k-file-error");
      }
    });
  }
});