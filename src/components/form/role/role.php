<bbn-form :scrollable="false"
          class="bbn-overlay"
          :source="source.roles"
          @submit.prevent="onSubmit"
          ref="form">
  <div class="bbn-overlay bbn-hpadded bbn-vspadded">
    <appui-usergroup-picker :multi="true"
                            class="bbn-h-100"
                            v-model="source.roles[role]"
                            :as-array="true"
                            :source="users"/>
  </div>
</bbn-form>