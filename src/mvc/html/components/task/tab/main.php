<div class="appui-task-tab-main bbn-padded bbn-100">
  <bbn-input type="hidden" name="id" :value="id"></bbn-input>
  <bbn-input type="hidden" name="ref" ref="ref" :value="ref"></bbn-input>

  <div class="bbn-task-form-container">
    <div class="bbn-task-info-ppl k-widget">
      <div class="bbn-block">
        <div class="bbn-block">
          <?=_("Created by")?>
          <br>
          <?=_("On")?>
        </div>
        <div class="bbn-block">
          <bbn-initial :user-id="id_user"></bbn-initial>
          {{appui_tasks.userName(id_user)}}
          <br>
          <span v-text="creation"></span>
        </div>
      </div>
      <div class="bbn-block" v-if="roles.workers">
        <div class="bbn-block"><?=_("Assigned to")?></div>
        <ul class="bbn-block">
          <li v-for="r in roles.workers"">
            <bbn-initial :user-id="r"></bbn-initial>
            {{appui_tasks.userName(r)}}
          </li>
        </ul>
      </div>
      <div class="bbn-block" v-if="roles.managers">
        <div class="bbn-block"><?=_("Supervised by")?></div>
        <ul class="bbn-block">
          <li v-for="r in roles.managers">
            <bbn-initial :user-id="r"></bbn-initial>
            {{appui_tasks.userName(r)}}
          </li>
        </ul>
      </div>
    </div>

    <div class="bbn-form-full">
      <bbn-input name="title"
                 autocomplete="off"
                 class="bbn-lg title"
                 placeholder="<?=_("Title/short description")?>"
                 required="required"
                 v-model="title"
                 @keydown.enter.prevent.stop
                 style="width: 100%"
                 :disabled="!canChange()"
      ></bbn-input>


      <div class="bbn-form-label"><?=_("Category")?></div>
      <div class="bbn-form-field">
        <bbn-dropdowntreeview name="type"
                              style="width: 500px"
                              required="required"
                              v-model="type"
                              :source="appui_tasks.categories"
                              data-value-field="id"
                              :disabled="!canChange()"
        ></bbn-dropdowntreeview>
      </div>

      <div class="bbn-form-label"><?=_("Priority")?></div>
      <div class="bbn-form-field">
        <div class="bbn-form-label" style="width: 140px">
          <div class="bbn-block">
            <bbn-dropdown v-model="priority"
                          name="priority"
                          style="width: 80px"
                          :source="[1,2,3,4,5,6,7,8,9]"
                          :disabled="!canChange()"
            ></bbn-dropdown>
          </div>
        </div>
        <div class="bbn-form-field">
          <div class="bbn-form-label" style="width: 140px"><?=_("Deadline")?></div>
          <div class="bbn-form-field">
            <bbn-datepicker name="deadline"
                            v-model="deadline"
                            @keydown="preventAll($event)"
                            format="yyyy-MM-dd"
                            :disabled="!canChange()"
            ></bbn-datepicker>
            <bbn-button v-if="deadline"
                        @click="removeDeadline"
                        icon="fa fa-times"
            ></bbn-button>
          </div>
        </div>
      </div>

      <div class="bbn-form-label" v-if="reference"><?=_("External reference")?></div>
      <div class="bbn-form-field" v-if="reference" v-html="reference"></div>

      <div class="bbn-form-label bbn-lg bbn-task-actions"><em v-text="stateText"></em></div>
      <div class="bbn-form-field bbn-lg bbn-task-actions">
        <div v-if="isActive()">
          <bbn-button v-if="canHold()" @click="hold" title="<?=_("Put on hold")?>" icon="fa fa-pause"></bbn-button>
          <bbn-button v-if="canResume()" @click="resume" title="<?=_("Resume")?>" icon="fa fa-play"></bbn-button>
          <bbn-button v-if="canClose()" @click="close" title="<?=_("Close") ?>" icon="fa fa-check"></bbn-button>
          <bbn-menu v-if="isAdded()"
                    class="bbn-iblock"
                    :source="rolesMenu"
                    @select="makeMe"
                    style="vertical-align: middle"
          ></bbn-menu>
          <bbn-button v-if="canPing()" @click="ping" title="<?=_("Ping workers")?>" icon="fa fa-hand-o-up"></bbn-button>
          <bbn-button v-if="isAdded()" @click="unmakeMe" title="<?=_("Unfollow the task")?>" icon="fa fa-user-times"></bbn-button>
        </div>
        <div v-if="isHolding()">
          <bbn-button v-if="canResume()" @click="resume" title="<?=_("Resume")?>" icon="fa fa-play"></bbn-button>
        </div>
        <div v-if="isClosed()">
          <bbn-button v-if="canOpen()" @click="reopen" title="<?=_("Reopen")?>" icon="fa fa-hand-o-left"></bbn-button>
        </div>
      </div>
    </div>

    <div class="bbn-line-breaker"> </div>

    <div class="bbn-form-label bbn-p" @click="showCommentAdder = true">
      <i class="fa fa-edit"></i> &nbsp; <?=_("Add a comment")?>
    </div>
    <div class="bbn-form-field"></div>

    <div class="bbn-form-full bbn-task-form-adder" v-if="showCommentAdder">
      <div class="bbn-form-label" style="width: 220px"><?=_("Title")?></div>
      <div class="bbn-form-field">
        <bbn-input name="comment_title" ref="comment_title" v-model="commentTitle" style="width: 100%"></bbn-input>
      </div>

      <div class="bbn-form-label">
        <?=_("Comment")?>
        <br>
        <bbn-dropdown class="comment_type"
                      :source="commentTypes"
                      ref="comment_type"
                      @change="changeCommentType"
        ></bbn-dropdown>
      </div>
      <div class="bbn-form-field">
        <component :is="commentType" name="comment" v-model="commentText" style="width: 100%"></component>
      </div>

      <div class="bbn-form-label"><?=_("Files")?></div>
      <div class="bbn-form-field bbn-task-files-container">
        <bbn-upload :save-url="appui_tasks.root + 'actions/file/upload/' + ref"
                    :remove-url="appui_tasks.root + 'actions/file/unupload/' + ref"
                    :localization="{select: 'Fichiers / Images'}"
                    thumb-not="<?php echo BBN_STATIC_PATH.'img/not_available-generic.png'?>"
                    thumb-waiting="<?php echo BBN_STATIC_PATH.'img/waiting-generic.png'?>"
        ></bbn-upload>
      </div>

      <div class="bbn-form-label"><?=_("Links")?></div>
      <div class="bbn-form-field">
        <div class="k-widget k-upload k-header">
          <div class="k-dropzone">
            <bbn-input name="link" ref="link" @keydown.enter.prevent="linkEnter" style="width: 100%" placeholder="<?=_("Type or paste your URL and press Enter to valid")?>"></bbn-input>
          </div>
          <table class="k-upload-files bbn-task-links-container" ref="links_container"></table>
        </div>
      </div>

      <div class="bbn-form-label"> </div>
      <div class="bbn-form-field">
        <bbn-button class="bbn-task-comment-button" @click="addComment" icon="fa fa-save"></bbn-button>
      </div>

    </div>
  </div>
  <div class="bbn-form-full bbn-task-comments">
    <div class="bbn-w-100 bbn-line-breaker" v-for="n in notes">
      <div>
        <div class="bbn-task-comment-author">
          <bbn-initial :user-id="n.id_user" :title="appui_tasks.userName(n.id_user)"></bbn-initial>
        </div>
        <div class="bbn-task-comment-author" style="margin-left: 5px">
          <a class="author">{{appui_tasks.userName(n.id_user)}}</a>
          <div class="metadata">
            <div class="date">{{renderSince(n.creation)}}</div>
            <!--<div class="rating">
              <i class="star icon"></i>
              5 Faves
            </div>-->
          </div>
        </div>
      </div>
      <div class="title bbn-lg" v-if="n.title" v-html="n.title"></div>
      <div class="text">
        <div v-if="n.content" v-html="n.content"></div>
        <p v-for="m in n.medias">
          <span style="margin-left: 2em">
            <a class="media" v-text="m.title" @click="downloadMedia(m.id)"></a>
          </span>
        </p>
      </div>
    </div>
  </div>
</div>