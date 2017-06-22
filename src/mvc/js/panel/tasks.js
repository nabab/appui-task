// Javascript Document
(function(){
  return {
    beforeMount: function(){
      bbn.vue.setComponentRule(this.source.root + 'components/', 'appui');
      bbn.vue.addComponent('task');
      bbn.vue.addComponent('task/tab/main');
      bbn.vue.addComponent('task/tab/people');
      bbn.vue.addComponent('task/tab/logs');
      bbn.vue.unsetComponentRule();
    },
    data: function(){
      return this.source;
    }
  }
})();