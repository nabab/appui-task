<bbn-table :source="mainPage.root + 'page/logs'"
           :filterable="true">
  <bbns-column :title="_('Date')"
               field="chrono"
               type="datetime"
               :width="120"/>
  <bbns-column :title="_('Task')"
               field="title"
               :render="renderTitle"/>
  <bbns-column :title="_('State')"
               field="state"
               :source="mainPage.optionsStates"
               :render="renderState"
               :width="120"/>
  <bbns-column :title="_('Log')"
               field="log"
               :filterable="false"/>
</bbn-table>