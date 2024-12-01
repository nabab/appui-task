<bbn-form :scrollable="false"
          class="bbn-overlay"
          :source="source.roles"
          @submit.prevent="onSubmit"
          ref="form">
  <div class="bbn-overlay bbn-spadding">
    <appui-usergroup-picker :multi="true"
                            bbn-model="source.roles[role]"
                            :as-array="true"
                            :source="users"
                            :filterable="true"
                            :selected-panel="true"
                            :show-only-new="!manage"/>
  </div>
</bbn-form>