<bbn-form class="appui-task-item-budget"
          :source="formSource"
          @submit.prevent="onSubmit">
  <div class="bbn-padding bbn-100">
    <bbn-numeric :decimals="2"
                  :min="0"
                  bbn-model="formSource.price"
                  class="bbn-w-100"/>
  </div>
</bbn-form>
