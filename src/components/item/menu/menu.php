<div :class="['bbn-flex-width', 'bbn-vmiddle', 'bbn-vxspadded', 'bbn-hspadded', {
       'bbn-alt-background': !!source.group,
       'bbn-alt-text': !!source.group
     }, source.cls]"
     @click="!!source.action ? source.action : false"
     @mouseover="onMouseOver"
     @mouseleave="onMouseLeave"
     :style="currentStyle">
  <div v-if="!!source.group"
       v-text="source.group"
       class="bbn-b bbn-c bbn-upper bbn-flex-fill"/>
  <template v-else>
    <div v-if="source.icon !== undefined"
         style="width: 1.8rem">
      <i v-if="source.icon"
         :class="[source.icon, 'bbn-m']"/>
    </div>
    <div v-text="source.text"
         class="bbn-flex-fill"/>
  </template>
  <i v-if="!!source.items && !!source.items.length"
     class="nf nf-fa-chevron_right"/>
</div>