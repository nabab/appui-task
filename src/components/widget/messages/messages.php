<div>
  <div v-for="n in source.notes"
       class="bbn-vmiddle bbn-flex-width bbn-vsmargin"
  >
    <bbn-initial :user-id="n.creator"
                 :height="25"
                 :width="25"
                 font-size="1em"
    ></bbn-initial>
    <span class="bbn-hsmargin bbn-flex-fill"
          v-text="shorten(html2text(n.content))"
    ></span>
    <span v-text="fdate(n.last_edit)"></span>
  </div>
</div>
