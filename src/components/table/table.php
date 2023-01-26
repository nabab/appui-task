<!-- HTML Document -->
<div class="appui-task-table bbn-overlay">
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
                 cls="bbn-c"
                 field="priority"
                 :component="$options.components.prioavatar"
                 :width="40"
                 :source="mainPage.priorities"/>
    <bbns-column title="<i class='nf nf-fa-tasks'></i>"
                 ftitle="<?=_("States")?>"
                 field="state"
                 :render="renderState"
                 :width="50"
                 cls="bbn-h-100 bbn-c"
                 :source="mainPage.source.options.states"/>
    <bbns-column title="<?=_("Last")?>"
                 field="last_action"
                 :width="120"
                 type="datetime"/>
    <bbns-column title="<?=_("Title")?>"
                 :min-width="300"
                 field="title"/>
    <bbns-column title="<?=_("Role")?>"
                 field="role"
                 :render="renderRole"
                 :width="100"
                 :source="mainPage.source.options.roles"/>
    <bbns-column title="<?=_("Type")?>"
                 field="type"
                 :render="renderType"
                 :width="150"
                 :max-width="300"
                 :source="mainPage.source.options.cats"/>
    <bbns-column title="<?=_("Duration")?>"
                 field="duration"
                 :render="renderDuration"
                 :width="70"
                 :filterable="false"/>
    <bbns-column title="<?=_("Budget")?>"
                 field="price"
                 type="money"
                 :render="renderBudget"
                 cls="bbn-r"
                 :width="100"/>
    <bbns-column title="<?=_("Reference")?>"
                 :min-width="150"
                 :max-width="300"
                 field="reference"
                 :filterable="false"/>
    <bbns-column title="<?=_("Creation Date")?>"
                 width="90"
                 field="creation_date"
                 type="date"/>
    <bbns-column title="<?=_("Deadline")?>"
                 width="90"
                 field="deadline"
                 type="date"/>
    <bbns-column title="<i class='nf nf-fa-comment'></i>"
                 ftitle="<?=_('#Notes')?>"
                 field="num_notes"
                 type="number"
                 :width="50"
                 cls="bbn-c"
                 :filterable="false"/>
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