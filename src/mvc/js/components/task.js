/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 07/06/2017
 * Time: 12:06
 */
(() => {
  return  {
    props: ['source'],
    created(){
      if ( this.source.roles ){
        if ( !this.source.roles.managers ){
          this.$set(this.source.roles, 'managers', []);
        }
        if ( !this.source.roles.workers ){
          this.$set(this.source.roles, 'workers', []);
        }
        if ( !this.source.roles.viewers ){
          this.$set(this.source.roles, 'viewers', []);
        }
      }
    }
  }
})();
