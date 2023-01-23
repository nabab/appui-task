# appui-task

## Global Roles

All users can create their own tasks, and assign who they want to it.  They always can see the tasks they are part of through a **role**.

These are special permissions that allow the users who hold them to have specific roles on all the non private tasks.  

They will be held forst as permission, but the goal is to have them in a specific table which will link them to an **account**.  

### Doers
- Account manager
  can see all tasks and change everything
- Account viewer
  can see all tasks
- Project manager
  can see all tasks and change everything but the budget that he doesn't see
- Project viewer
  can see all tasks without the budget
- Assigner
  can see all "incoming" tasks and assign roles/type/priority

### Deciders
- Financial manager
  can see and accept all the tasks with budget
- Financial viewer
  can see all the tasks with budget, accepted or not
- Project supervisor
  can see and modify all the tasks with budget, accepted or not, without seeing budget informations

