/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 07/06/2017
 * Time: 12:06
 */
(() => {
  return  {
    props: ['source', 'tasks'],
    created(){
      if ( this.source.roles ){
        bbn.fn.each(this.tasks.source.roles, (i, n) => {
          if ( !this.source.roles[n] ){
            this.$set(this.source.roles, n, []);
          }
        });
      }
    }
  }
})();
