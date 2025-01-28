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
             :filters="filters"
             :showable="true"
             :resizable="true">
    <bbns-column label="<?= _('ID') ?>"
                 field="id"
                 :width="270"
                 :invisible="true"
                 :showable="true"/>
    <bbns-column label="<i class='nf nf-fa-user'></i>"
                 flabel="<?= _('User') ?>"
                 field="id_user"
                 :component="$options.components.useravatar"
                 :width="38"
                 :source="users"/>
    <bbns-column label="<i class='nf nf-fa-exclamation'></i>"
                 flabel="<?= _('Priority') ?>"
                 cls="bbn-c"
                 field="priority"
                 :component="$options.components.prioavatar"
                 :width="40"
                 :source="mainPage.priorities"/>
    <bbns-column label="<i class='nf nf-fa-tasks'></i>"
                 flabel="<?= _("States") ?>"
                 field="state"
                 :render="renderState"
                 :width="50"
                 cls="bbn-h-100 bbn-c"
                 :source="states"/>
    <bbns-column label="<?= _("Ref.") ?>"
                 flabel="<?= _("Reference number") ?>"
                 field="ref"
                 :width="100"
                 cls="bbn-c"/>
    <bbns-column label="<?= _("Last") ?>"
                 field="last_action"
                 :width="130"
                 type="datetime"/>
    <bbns-column label="<?= _("Title") ?>"
                 :min-width="300"
                 field="title"/>
    <bbns-column label="<?= _("Role") ?>"
                 field="role"
                 :render="renderRole"
                 :width="100"
                 :source="mainPage.source.options.roles"/>
    <bbns-column label="<?= _("Type") ?>"
                 field="type"
                 :render="renderType"
                 :width="150"
                 :max-width="300"
                 :source="mainPage.source.options.cats"/>
    <bbns-column label="<?= _("Duration") ?>"
                 field="duration"
                 :render="renderDuration"
                 :width="70"
                 :filterable="false"/>
    <bbns-column label="<?= _("Budget") ?>"
                 field="price"
                 type="money"
                 :render="renderBudget"
                 cls="bbn-r"
                 :width="100"/>
    <bbns-column label="<?= _("Budget Sub-Tasks") ?>"
                 field="children_price"
                 type="money"
                 :render="renderBudgetSubtasks"
                 cls="bbn-r"
                 :width="100"
                 :filterable="false"
                 :sortable="false"/>
    <bbns-column label="<?= _("Reference") ?>"
                 :min-width="150"
                 :max-width="300"
                 field="reference"
                 :filterable="false"/>
    <bbns-column label="<?= _("Creation Date") ?>"
                 width="90"
                 field="creation_date"
                 type="date"/>
    <bbns-column label="<?= _("Deadline") ?>"
                 width="90"
                 field="deadline"
                 type="date"/>
    <bbns-column label="<i class='nf nf-fa-comment'></i>"
                 flabel="<?= _('#Notes') ?>"
                 field="num_notes"
                 type="number"
                 :width="50"
                 cls="bbn-c"
                 :filterable="false"/>
    <bbns-column label=""
                 :width="50"
                 :buttons="[{
                           title: '<?= _('See task') ?>',
                           icon: 'nf nf-fa-eye',
                           action: openTask,
                           notext: true
                           }]"
                 fixed="right"/>
  </bbn-table>
</div>