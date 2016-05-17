// Javascript Document
//$(ele).closest(".k-content").css("padding", "0px");

var tabnav = $("#appui_task_tabnav").tabNav({
  baseTitle: 'Projects - ',
  scrollable: false,
  list: [{
    url: "search",
    static: true,
    load: true
  }]
});
$.each(data.groups, function(i, v){
  data.groups[i].items = $.map($.grep(appui.app.apst.users, function(user){
    return user.active && (user.id_group === v.id);
  }), function(user){
    user.id = user.value;
    return user;
  });
});

appui.tasks = {
  priority_colors: ['#F00', '#F40', '#F90', '#FC0', '#FF0', '#AD0', '#5B0', '#090', '#CCC', '#FFF'],
  categories: data.categories,
  options: data.options,
  typeField: function(container){
  	var ddTree = $("input[name=type]", container).kendoDropDownTreeView({
      treeview: {
        select: function(e){
          ddTree.element.val(e.sender.dataItem(e.node).id).change();
        },
        dataTextField: "text",
        dataValueField: "id",
        dataSource: new kendo.data.HierarchicalDataSource({
          data: appui.tasks.categories,
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
    return ddTree;
	},
  mainView: function(info, ele){
    var textarea = $("textarea", ele),
        cont_textarea = textarea.parent();
    textarea.kendoEditor({
      encoded: false,
      tools: [
        "formatting",
        "bold",
        "italic",
        "underline",
        "justifyLeft",
        "justifyCenter",
        "justifyRight",
        "insertUnorderedList",
        "insertOrderedList",
        "indent",
        "outdent",
        "createLink",
        "unlink"
      ]
    });
    kendo.bind(ele, info);
    var ddTree = appui.tasks.typeField(ele),
        uploadedFiles = [],
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
          var uploader = $('<input name="file" type="file"/>');
          uploadWrapper.empty().append(uploader);
          uploader.kendoUpload({
            localization: {
              select: "Fichiers / Images"
            },
            async: {
              saveUrl: data.root + "upload/" + info.ref,
              removeUrl: data.root + "unupload/" + info.ref
            },
            template: function(e){
              var size,
                  unit;
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
                      st += '<img src="' + data.root + 'image/tmp/' + info.ref + v.h96 + '">';
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
                  '<span class="k-icon k-i-close k-delete" title="Supprimer"></span>' +
                  '</button>' +
                  '</td></tr></tbody></table></div>';
                return st;
              }
            },

            // @todo Voir la taille
            files: uploadedFiles,
            upload: function(e){
              if ( e.files && e.files[0] ){
                var idx = appui.fn.search(uploadedFiles, "name", e.files[0].name);
                if ( idx > -1 ){
                  e.preventDefault();
                  appui.fn.alert(data.lng.file_exists);
                  return false;
                }
              }
            },
            success: function(e){
              if ( e.response && e.response.files && e.files && (e.response.success === 1) ){
                var file = uploader.val();
                if ( e.operation === 'upload' ){
                  $.each(e.response.files, function(i, f){
                    if ( f.size ){
                      uploadedFiles.push(f);
                    }
                  });
                  createUpload();
                  uploadWrapper.redraw();
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
                appui.fn.alert(data.lng.problem_file);
              }
            },
            error:function(e){
              appui.fn.alert(data.lng.error_uploading)
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
          '<span title="Supprimer" class="k-icon k-i-close k-delete"></span>' +
          '</button>' +
          '</tr></table>' +
          '</div>' +
          '</td></tr>'
        );
        $target.redraw();
        $input.val("");
        $li = $target.find("tr:last").parent().closest("tr");
        appui.fn.post(data.root + "link_preview", {url: v, ref: info.ref}, function(d){
          if ( d.res && d.res.realurl ){
            $li.find("td.k-file").removeClass("k-file-progress").addClass("k-file-success");
            if ( d.res.pictures ){
              $.each(d.res.pictures, function(i, v){
                if ( v.h96 ){
                  $li.find("td.appui-task-link-image").html('<img src="pm/image/tmp/' + info.ref + '/' + v.h96 + '">');
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
    appui.fn.log("INFO", ddTree, info);
  },
  rolesView: function(info, ele){
    var $tree,
        $li,
        roles = {},
        tmp,
        types_roles = ['managers', 'viewers', 'workers'],
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
      loadOnDemand: false,
      select: function (e) {
        if ( data.picker ){
          var r = this.dataItem(e.node);
          $(data.picker).val(r.id).trigger("change");
          appui.fn.closeAlert();
        }
      },
      template: function (e) {
        if ( e.item.is_parent ){
          return '<i class="fa fa-users"> </i> ' + e.item.text;
        }
        return apst.userFull(e.item.id);
      }
    }).data("kendoTreeView");
    $li = $("li.k-item", $tree.element);
    /*
    $("input:first", ele).keyup(function(){
      var v = $(this).val().toLowerCase();
      $li.filter(":hidden").show();
      if ( v.length ){
        $li.filter(function(){
          var txt = appui.fn.removeAccents($(this).find("span:not(.k-icon):first").text().toLowerCase());
          return txt.indexOf(v) === -1;
        }).hide();
      }
      var data = treeDS.data();
      data.forEach(function(a){
        appui.fn.log(a, this);
        a.filter({field: "text", operator: "contains", value: $(this).val()});
      })
      appui.fn.log(treeDS, treeDS.data());
      treeDS.filter({field: "text", operator: "contains", value: $(this).val()});
      Porca putana!
    });
    */

    $.each(types_roles, function(i, v){
      if ( tmp = info.roles.get(v) ){
        roles[v] = tmp;
      }
    });
    $li.draggable(dragCfg).each(function(){
      var dataItem = $tree.dataItem(this);
      if ( !dataItem.is_parent ){
        for ( var n in roles ){
          if ( $.inArray(dataItem.id, roles[n]) > -1 ){
            appui.tasks.addUser(info, dataItem, $(".appui-task-" + n, ele)[0], false);
          }
        }
      }
    });

    $("div.appui-task-assigned", ele).droppable({
      accept: ".appui-task-usertree li",
      hoverClass: "bbn-dropable-hover",
      activeClass: "bbn-dropable-active",
      drop: function(e, ui){
        var dataItem = $tree.dataItem(ui.draggable),
            $ele = this,
            items = [];
        if ( dataItem.is_parent ){
          if ( dataItem.items && dataItem.items.length ){
            items = dataItem.items;
          }
        }
        else{
          items.push(dataItem);
        }
        $.each(items, function(i, v){
          appui.tasks.addUser(info, v, $ele, true);
        });
      }
    });
  },
  logsView: function(info, ele){
    var tab = $("div.tab-logs", ele);
    tab.kendoGrid({
      dataSource: {
        sort: {
          field: "chrono",
          dir: "desc"
        },
        transport: {
          read: function(e){
            appui.fn.post(data.root + 'logs', {id_task: info.id}, function(d){
              if ( d.data ){
                return e.success(d.data);
              }
            })
          }
        },
        schema: {
          model: {
            fields: {
              id_user: {
                type: "number"
              },
              date: {
                type: "date"
              },
              action: {
                type: "string"
              }
            }
          }
        }
      },
      autoBind: false,
      sortable: true,
      columns: [{
        title: " ",
        width: 38,
        attributes: {
          style: "padding: 1px"
        },
        sortable: false,
        template: function(e){
          return apst.userAvatarImg(e.id_user);
        }
      }, {
        title: data.lng.user,
        title: data.lng.user,
        field: "id_user",
        width: 150,
        template: function(e){
          return apst.userName(e.id_user);
        }
      }, {
        title: data.lng.date,
        field: "chrono",
        width: 150,
        template: function(e){
          return appui.fn.fdate(e.chrono);
        }
      }, {
        title: data.lng.action,
        field: "action",
        encoded: false
      }]
    });
  },
  addUser: function(info, dataItem, cont, insert){
    var id = dataItem.get("id"),
        $ul = $(cont).find("ul"),
        $input = $(cont).find("input"),
        prop = $input.attr("name"),
        $tree = $("div.appui-task-usertree", ele).data("kendoTreeView"),
		    $ele = $tree.findByUid(dataItem.get("uid"));
    if ( $ele.css("display") !== 'none' ){
      var v = info.roles.get(prop);
      if ( !$.isArray(v) ){
        info.roles.set(prop, []);
      }
      if ( $.inArray(id, v) === -1 ){
        info.roles[prop].push(id);
        $input.val(JSON.stringify(info.roles[prop].toJSON()));
        if ( insert ){
          appui.fn.post(data.root + 'actions/role/insert', {id_task: info.id, role: prop, id_user: id}, function(d){
            if ( !d.success ){
              appui.fn.alert();
            }
          });
        }
      }
      $ul.append(
        $('<li class="k-item">' + $ele.html() + '</li>').mousedown(function(e){
          e.preventDefault();
          e.stopImmediatePropagation();
          e.stopPropagation();
          return false;
        })
      );
      $ele.hide();
      $ul.find("li.k-item:last span:last").append(
        '&nbsp;',
        $('<i class="fa fa-times appui-p"/>').data("id", id).data("ele", $ele).click(function(){
          var $$ = $(this);
          $$.data("ele").show();
          var idx = $.inArray($$.data("id"), info.roles.get(prop));
          if ( idx > -1 ){
            appui.fn.post(data.root + 'actions/role/delete', {id_task: info.id, role: prop, id_user: id}, function(d){
              if ( !d.success ){
                appui.fn.alert();
              }
              else{
                info.roles[prop].splice(idx, 1);
                $input.val(JSON.stringify(info.roles[prop].toJSON()));
                $$.closest("li").remove();
              }
            });
          }
        })
      );
    }

  },
  update: function(prop, val, id){
    appui.fn.post(data.root + 'actions/task/update', {
      id_task: id,
      prop: prop,
      val: val
    }, function(d){
      if ( !d.success ){
        appui.fn.alert();
      }
    })
  },
  _init: function(info, ele){
    if ( info.notes ){
      $.each(info.notes, function(i, v){
        var m = new moment(v.creation);
        info.notes[i].since = m.fromNow();
      });
    }
    return kendo.observable($.extend(info, {
      creator: apst.userFull(info.id_user),
      creation: appui.fn.fdate(info.creation_date),
      ref: (new moment()).unix(),
      add_code: function(){},
      add_link: function(){},
      add_comment: function(e){
        var ko = this,
            v = {
              id: ko.id,
              title: $("input[name=comment_title]", ele).val() || '',
              text: $("textarea[name=comment]", ele).val() || ''
            };
        if ( !v.title && !v.text ){
          appui.fn.alert(data.lng.no_comment_text)
        }
        else{
          appui.fn.post(data.root + 'actions/comment/insert', v, function(d){
            if ( d.success ){
              var cr = new Date().getSQL(1),
                  m = new moment(cr);
              ko.notes.push({
                content: v.text,
                title: v.title,
                id_note: d.success,
                id_user: appui.env.userId,
                creation: new Date().getSQL(1),
                since: m.fromNow(),
                version: 1,
              });
              $("textarea[name=comment]", ele).val('').trigger("change");
              $("input[name=comment_title]", ele).val('').trigger("change").parent().parent().hide();
            }
            else{
              appui.f.alert();
            }
          });
        }
      },
      preventEnter: function(e){
        if ( e.key === "Enter" ){
          e.preventDefault();
          e.stopPropagation();
        }
      },
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
            textarea.kendoEditor({
              tools: [
                "formatting",
                "bold",
                "italic",
                "underline",
                "justifyLeft",
                "justifyCenter",
                "justifyRight",
                "insertUnorderedList",
                "insertOrderedList",
                "indent",
                "outdent",
                "createLink",
                "unlink"
              ]
            });
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
      types_roles: data.roles,
      change_role: function(){
        var role = $("input[name=role]", ele).data("kendoDropDownList").value();
        if ( role ){
          appui.fn.window("usergroup/picker", {picker: "#appui_pm_form_container input[name=id_user]"}, 350, 700);
        }
      },
      add_user: function(){
        var dataItem = this,
            id_user = $("input[name=id_user]", ele).val(),
            rolePicker = $("input[name=role]", ele).data("kendoDropDownList"),
            role = rolePicker.value(),
            frole = appui.fn.get_field(dataItem.types_roles, "value", role, "text");
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
      update: function(e){
        if ( e.sender ){
          return appui.tasks.update($(e.sender.element).attr("name"), e.sender.value(), info.id);
        }
        return appui.tasks.update($(e.target).attr("name"), $(e.target).val(), info.id);
      }
    }))
  },
  create: function(info){
    if ( info.id ){
      info = appui.tasks._init(info, ele);
      var tabstrip = $("#appui_task" + info.id, ele);
      tabnav.tabNav("setColor", appui.tasks.priority_colors[info.priority-1], tabstrip);
      if ( tabstrip.length ){
        tabstrip.tabNav({
          list: [{
            url: "main",
            title: '<i class="fa fa-eye"> </i> &nbsp; ' + data.lng.global_view,
            content: $("#tpl-task_tab_main").html(),
            static: true,
            callonce: function(ele){
              return appui.tasks.mainView(info, ele);
            }
          }, {
            url: "people",
            title: '<i class="fa fa-users"> </i> &nbsp; ' + data.lng.roles,
            content: $("#tpl-task_tab_roles").html(),
            static: true,
            callonce: function(ele){
              return appui.tasks.rolesView(info, ele);
            }
          }, {
            url: "logs",
            title: '<i class="fa fa-list"> </i> &nbsp; ' + data.lng.journal,
            content: '<div class="tab-logs appui-full-height"></div>',
            static: true,
            callonce: function(ele){
              return appui.tasks.logsView(info, ele);
            },
            callback: function(ele){
              $("div.tab-logs", ele).data("kendoGrid").dataSource.read();
            }
          }]
        })
      }
    }
  },
  formNew: function(v){
    appui.fn.alert($("#tpl-task_form_new").html(), data.lng.new_task, 800, 250, function(cont){
      $("input[name=title]", cont).val(v);
      appui.tasks.typeField(cont);
      $("form").attr("action", data.root + 'actions/task/insert').data("script", function(e, f){
        if ( e.success ){
          appui.fn.closeAlert();
          tabnav.tabNav("link", 'tasks/display_' + e.success);
        }
        else{
          appui.fn.alert();
        }
      });
    })
  }
};
