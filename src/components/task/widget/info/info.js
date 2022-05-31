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
      minDate(){
        return dayjs().format('YYYY-MM-DD');
      }
    }
  }
})();