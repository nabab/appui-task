(() => {
  return {
    computed: {
      showStatus(){
        return this.task.canChange
          && ((this.task.isUnapproved && this.canClose)
            || (this.task.isActive
              && (this.task.canStart || this.task.canHold || this.task.canResume || this.task.canClose)
                || (this.task.isHolding && this.task.canResume)
                || (this.task.isClosed && this.task.canOpen)))
      },
      showRoles(){
        return this.task.canBecomeManager
          || this.task.canBecomeWorker
          || this.task.canBecomeViewer
          || this.task.canBecomeDecider;
      },
      showOther(){
        return this.task.canPing;
      },
      showBudget(){
        return (this.task.canApprove && !this.task.isApproved)
          || (!!this.source.price && this.task.isAdmin && !this.task.isClosed && !!this.source.lastChangePrice);
      },
      showTracker(){
        return this.task.isOngoing
          && (this.task.isWorker || this.task.isManager)
      }
    }
  }
})();