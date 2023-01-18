(() => {
  return {
    props: {
      source: {
        type: Object
      }
    },
    data(){
      return {
        currentStyle: {
          backgroundColor: this.source.backgroundColor,
          color: this.source.color
        }
      }
    },
    methods: {
      onMouseOver(){
        if (!this.group) {
          this.currentStyle.backgroundColor = '';
          this.currentStyle.color = '';
        }
      },
      onMouseLeave(){
        if (!this.group) {
          this.currentStyle.backgroundColor = this.source.backgroundColor;
          this.currentStyle.color = this.source.color;
        }
      }
    }
  }
})();