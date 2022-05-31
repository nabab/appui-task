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
      html2text: bbn.fn.html2text,
      fdate: bbn.fn.fdate
    }
  }
})();