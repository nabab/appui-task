// Javascript Document
$(ele).closest(".k-content").css("padding", "0px");

$("#appui_task_tabnav").tabNav({
  baseTitle: 'Projects - ',
  scrollable: false,
  list: [{
    title: '<i class="fa fa-plus-square"> </i> &nbsp; ' + data.lng.new_task + ' / ' + data.lng.search,
    content: ' ',
    url: "search",
    static: true,
    load: true
  }, {
    title: '<i class="fa fa-file-text-o"> </i> &nbsp; ' + data.lng.demo_task,
    content: ' ',
    url: "new",
    static: true,
    load: true
  }, {
    title: '<i class="fa fa-clock-o"> </i> &nbsp; ' + data.lng.my_ongoing_tasks,
    content: ' ',
    url: "ongoing",
    static: true,
    load: true
  }, /*{
    title: data.lng.timeline,
    content: '<p class="appui-c appui-lg">' + data.lng.soon + '</p>',
    url: "timeline",
    static: true,
    callback: function(cont, idx, dt, widget){
      appui.fn.log(cont, idx, dt, widget);
      //appui.fn.log(this.options.current);
    }
  }, {
    title: data.lng.all_tasks,
    content: '<p class="appui-c appui-lg">' + data.lng.soon + '</p>',
    url: "all",
    static: true,
    callback: function(cont, idx, dt, widget){
      appui.fn.log(cont, idx, dt, widget);
      //appui.fn.log(this.options.current);
    }
  }, */{
    title: '<i class="fa fa-folder-open-o"> </i> &nbsp; ' + data.lng.opened_tasks,
    url: "tasks",
    static: true,
    content: '<div class="appui-task-opened-tabstrip appui-full-height"></div>' +
    	'<div class="appui-task-opened-desc container-placeholder appui-lg">' +
    		'<i class="fa fa-question-circle"> </i> &nbsp;' +
    		'Opened projects come here' +
    	'</div>',
    callonce: function(ele){
      $(".appui-task-opened-tabstrip", ele).tabNav({
        activate: function(ele, idx, ui){
          ele.parent().next().hide()
        },
        afterClose: function(ele, idx, ui){
          appui.fn.log("SUR AFTER CLOSE", ele, ui, ui.getList());
          if ( !ui.getList().length ){
            ele.parent().next().show()
          }
        },
        transform: function(obj){
          if ( obj.data.info && obj.data.info.priority ){
            obj.bcolor = appui.tasks.priority_colors[obj.data.info.priority-1];
            obj.fcolor = obj.data.info.priority > 6 ? '#666' : '#EEE';
          }
          return obj;
        }
      });
    }
  }]
});

appui.tasks = {
  priority_colors: ['#F00', '#F40', '#F90', '#FC0', '#FF0', '#AD0', '#5B0', '#090', '#CCC', '#FFF'],
  categories: data.categories,
  options: data.options,
  mainView: function(info, ele){
    var textarea = $("textarea", ele),
        cont_textarea = textarea.parent(),
        old_type = 'text',
    		ref = (new moment()).unix();
    kendo.bind(ele, $.extend(info, {
      ref: ref,
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
      roles: data.roles,
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
    }));
    var ddTree = $("input[name=type]", ele).kendoDropDownTreeView({
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
        }).data("kendoDropDownTreeView"),
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
          appui.fn.log("CREATE", uploadedFiles);
          var uploader = $('<input name="file" type="file"/>');
          uploadWrapper.empty().append(uploader);
          uploader.kendoUpload({
            async: {
              saveUrl: data.root + "upload/" + ref,
              removeUrl: data.root + "unupload/" + ref
            },
            template: function(e){
              appui.fn.log("TEM<PLATE", e);
              var size,
                  unit;
              appui.fn.log(e);
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
                      st += '<img src="' + data.root + 'image/tmp/' + ref + v.h96 + '">';
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
              appui.fn.log("UPLOAD", e);
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
              appui.fn.log("SUCCESS", e);
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
        appui.fn.post(data.root + "link_preview", {url: v, ref: ref}, function(d){
          if ( d.res && d.res.realurl ){
            appui.fn.log("ok", d.res);
            $li.find("td.k-file").removeClass("k-file-progress").addClass("k-file-success");
            if ( d.res.pictures ){
              $.each(d.res.pictures, function(i, v){
                if ( v.h96 ){
                  $li.find("td.appui-task-link-image").html('<img src="pm/image/tmp/' + ref + '/' + v.h96 + '">');
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
    appui.fn.log("INFO", info);
  },
  create: function(info){
    if ( info.id ){
      var tabstrip = $("#appui_task" + info.id);
      if ( tabstrip.length ){
        tabstrip.tabNav({
          list: [{
            url: "main",
            title: '<i class="fa fa-info-circle"> </i> &nbsp; Vue d\'ensemble',
            content: $("#tpl-task_tab_main").html(),
            static: true,
            callonce: function(ele){
              return appui.tasks.mainView(info, ele);
            }
          }, {
            url: "people",
            title: '<i class="fa fa-users"> </i> &nbsp; Rôles',
            content: '<div class="tab-full appui-full-height"></div>',
            static: true
          }, {
            url: "full",
            title: '<i class="fa fa-eye"> </i> &nbsp; Vue complète',
            content: '<div class="tab-full appui-full-height"></div>',
            static: true
          }, {
            url: "logs",
            title: '<i class="fa fa-list"> </i> &nbsp; Journal des évènements',
            content: '<div class="tab-logs appui-full-height"></div>',
            static: true
          }]
        })
      }
    }
  }
};