<div :class="['bbn-flex-width', 'bbn-vmiddle', 'bbn-vxspadding', 'bbn-hspadding', {
       'bbn-alt-background': !!source.group,
       'bbn-alt-text': !!source.group
     }, source.cls]"
     @click="!!source.action ? source.action : false"
     @mouseover="onMouseOver"
     @mouseleave="onMouseLeave"
     :style="currentStyle">
  <div bbn-if="!!source.group"
       bbn-text="source.group"
       class="bbn-b bbn-c bbn-upper bbn-flex-fill"/>
  <template bbn-else>
    <div bbn-if="source.icon !== undefined"
         style="width: 1.8rem">
      <i bbn-if="source.icon"
         :class="[source.icon, 'bbn-m']"/>
    </div>
    <div bbn-text="source.text"
         class="bbn-flex-fill"/>
  </template>
  <i bbn-if="!!source.items && !!source.items.length"
     class="nf nf-fa-chevron_right"/>
</div>