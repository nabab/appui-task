/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 26/04/2018
 * Time: 10:50
 */
(() => {
  return {
    props: ['source'],
    methods: {
      shorten: bbn.fn.shorten,
      minDate(){
        return dayjs().format('YYYY-MM-DD');
      },
      downloadDoc(docId){
        if (!!docId) {
          this.postOut(this.root + 'download/media/' + docId);
        }
      },
      isYou(id){
        return appui.user.id === id;
      }
    },
    components: {
      priority: {
        template: `
          <div :class="[source.class, 'bbn-spadding', 'bbn-c']"
               bbn-text="source.text"/>
        `,
        props: {
          source: {
            type: Object
          }
        }
      }
    }
  }
})();