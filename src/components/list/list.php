<!-- HTML Document -->
<div class="appui-task-list bbn-overlay">
  <bbn-table :source="mainPage.root + 'data/list'"
              :data="currentData"
              ref="tasksTable"
              :info="true"
              :pageable="true"
              :sortable="true"
              :order="[{field: 'last_action', dir: 'DESC'}]"
              class="appui-task-list-table bbn-no-border"
              :filterable="true"
              :filters="filters">
    <bbns-column title="<i class='nf nf-fa-user'></i>"
                  ftitle="<?=_('User')?>"
                  field="id_user"
                  :component="$options.components.useravatar"
                  :width="38"
                  :source="users"/>
    <bbns-column title="<i class='nf nf-fa-exclamation'></i>"
                  ftitle="<?=_('Priority')?>"
                  field="priority"
                  :cls="priorityClass"
                  :width="40"
                  :source="priority"/>
    <bbns-column title="<i class='nf nf-fa-comment'></i>"
                  ftitle="<?=_('#Notes')?>"
                  field="num_notes"
                  :width="50"
                  cls="bbn-c"
                  :filterable="false"/>
    <bbns-column title="<i class='nf nf-fa-tasks'></i>"
                  ftitle="<?=_("States")?>"
                  field="state"
                  :render="renderState"
                  :width="50"
                  cls="bbn-h-100 bbn-c"
                  :source="mainPage.source.options.states"/>
    <bbns-column title="<?=_("Last")?>"
                  field="last_action"
                  :render="renderLast"
                  :width="100"
                  type="datetime"/>
    <bbns-column title="<?=_("Role")?>"
                  field="role"
                  :render="renderRole"
                  :width="100"
                  :source="mainPage.source.options.roles"/>
    <bbns-column title="<?=_("Type")?>"
                  field="type"
                  :render="renderType"
                  :width="150"
                  style="max-width: 300px"
                  :source="mainPage.source.options.cats"/>
    <bbns-column title="<?=_("Duration")?>"
                  field="duration"
                  :render="renderDuration"
                  :width="70"
                  :filterable="false"/>
    <bbns-column title="<?=_("Title")?>"
                  field="title"/>
    <bbns-column title="<?=_("Reference")?>"
                  field="reference"
                  :filterable="false"/>
    <bbns-column title="<?=_("Creation Date")?>"
                  field="creation_date"
                  :render="renderCreationDate"
                  type="datetime"/>
    <bbns-column title="<?=_("Deadline")?>"
                  field="deadline"
                  :render="renderDeadline"
                  type="datetime"/>
    <bbns-column title=""
                  :width="50"
                  :buttons="[{
                    title: '<?=_('See task')?>',
                    icon: 'nf nf-fa-eye',
                    action: openTask,
                    notext: true
                  }]"
                  fixed="right"/>
  </bbn-table>
</div>
