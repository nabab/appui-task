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
        $.each(this.tasks.source.roles, (n, i) => {
          if ( !this.source.roles[n] ){
            this.$set(this.source.roles, n, []);
          }
        });
      }
    }
  }
})();
