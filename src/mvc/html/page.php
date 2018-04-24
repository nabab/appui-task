<div class="appui-tasks bbn-full-screen">
  <bbn-tabnav class="appui_task_tabnav"
              :scrollable="true"
              :autoload="true"
  >
    <bbn-tab url="search"
             :static="true"
             :load="true"
             :title="'<i class=\'fa fa-home\'> </i>&nbsp;<?=_("New task")?> / <?=_("Search")?>'"
    ></bbn-tab>
  </bbn-tabnav>
</div>
<script v-for="temp in source.templates" :id="temp.id" type="text/x-template" v-html="temp.html"></script>