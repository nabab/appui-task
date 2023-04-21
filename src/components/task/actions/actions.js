(() => {
  return {
    mixins: [
      appuiTaskMixin
    ],
    computed: {
      showStatus(){
        return this.canReopen
          || this.canRemoveTask
          || (this.canChange
            && ((this.isUnapproved && this.canClose)
              || (this.isActive
                && (this.canStart || this.canHold || this.canResume || this.canClose)
                  || (this.isHolding && this.canResume)
                  || (this.isClosed && this.canReopen))))
      },
      showRoles(){
        return this.canBecomeManager
          || this.canBecomeWorker
          || this.canBecomeViewer
          || this.canBecomeDecider;
      },
      showOther(){
        return this.canPing;
      },
      showBudget(){
        return this.canApprove
          || ((this.isAdmin || this.isGlobal || this.isAccountManager)
            && !this.isClosed
            && !this.isCanceled
            && !this.source.children_price
            && !this.source.parent_has_price);
      },
      showTracker(){
        return this.isOngoing
          && (this.isWorker || this.isManager)
      }
    }
  }
})();