<div>
  <div v-for="n in source.notes"
       class="bbn-vmiddle bbn-flex-width bbn-vsmargin"
  >
    <bbn-initial :user-id="n.creator"
                 :height="20"
                 :width="20"
    ></bbn-initial>
    <span class="bbn-hsmargin bbn-flex-fill"
          v-text="shorten(html2text(n.content))"
    ></span>
    <span v-text="fdate(n.last_edit)"></span>
  </div>
</div>
