<bbn-form :scrollable="false"
          class="bbn-overlay"
          :source="source.roles"
          @submit.prevent="onSubmit"
          ref="form">
  <div class="bbn-overlay bbn-spadded">
    <appui-usergroup-picker :multi="true"
                            v-model="source.roles[role]"
                            :as-array="true"
                            :source="users"
                            :filterable="true"
                            :selected-panel="true"/>
  </div>
</bbn-form>