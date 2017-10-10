<div class="appui-task-tab-main bbn-padded bbn-full-screen">
  <!--<div :class="{'bbn-task-form-container': true, 'bbn-h-100': showCommentAdder}">-->
  <div class="bbn-task-form-container bbn-h-100">
    <div class="bbn-flex-height">
      <div class="bbn-task-info-ppl k-widget">
        <div class="bbn-block">
          <div class="bbn-block">
            <?=_("Created by")?>
            <br>
            <?=_("On")?>
          </div>
          <div class="bbn-block">
            <bbn-initial :user-id="source.id_user"></bbn-initial>
            <span v-text="userName(source.id_user)"></span>
            <br>
            <span v-text="creation"></span>
          </div>
        </div>
        <div class="bbn-block" v-if="source.roles.workers && source.roles.workers.length">
          <div class="bbn-block"><?=_("Assigned to")?></div>
          <ul class="bbn-block">
            <li v-for="r in source.roles.workers">
              <bbn-initial :user-id="r"></bbn-initial>
              <span v-text="userName(r)"></span>
            </li>
          </ul>
        </div>
        <div class="bbn-block" v-if="source.roles.managers && source.roles.managers.length">
          <div class="bbn-block"><?=_("Supervised by")?></div>
          <ul class="bbn-block">
            <li v-for="r in source.roles.managers">
              <bbn-initial :user-id="r"></bbn-initial>
              <span v-text="userName(r)"></span>
            </li>
          </ul>
        </div>
      </div>

      <div class="bbn-w-100">
        <bbn-input autocomplete="off"
                   class="bbn-lg title bbn-w-100"
                   placeholder="<?=_("Title/short description")?>"
                   v-model="source.title"
                   @keydown.enter.prevent.stop
                   :disabled="!canChange"
        ></bbn-input>
        <div class="bbn-grid-fields bbn-w-50 bbn-padded">
          <div><?=_("Category")?></div>
          <bbn-dropdown :source="categories"
                        v-model="source.type"
                        :value="source.type"
                        :disabled="!canChange"
                        group="group"
                        class="bbn-w-100"
          ></bbn-dropdown>
          <div><?=_("Priority")?></div>
          <div class="bbn-grid-fields">
            <bbn-dropdown v-model="source.priority"
                          style="width: 80px"
                          :source="[1,2,3,4,5,6,7,8,9]"
                          :disabled="!canChange"
            ></bbn-dropdown>
            <div class="bbn-grid-fields">
              <div><?=_("Deadline")?></div>
              <div>
                <bbn-datepicker v-model="source.deadline"
                                @keydown="preventAll($event)"
                                format="yyyy-MM-dd"
                                :disabled="!canChange"
                ></bbn-datepicker>
                <bbn-button v-if="source.deadline"
                            @click="removeDeadline"
                            icon="fa fa-times"
                ></bbn-button>
              </div>
            </div>
          </div>
          <div v-if="source.reference"><?=_("External reference")?></div>
          <div v-if="source.reference"
               v-html="source.reference"
          ></div>

          <div class="bbn-lg bbn-task-actions">
            <em v-text="stateText"></em>
          </div>
          <div class="bbn-lg bbn-task-actions">
            <div v-if="isActive">
              <bbn-button v-if="canStart"
                          @click="start"
                          title="<?=_("Put on ongoing")?>"
                          icon="fa fa-play"
              ></bbn-button>
              <bbn-button v-if="canHold"
                          @click="hold"
                          title="<?=_("Put on hold")?>"
                          icon="fa fa-pause"
              ></bbn-button>
              <bbn-button v-if="canResume"
                          @click="resume"
                          title="<?=_("Resume")?>"
                          icon="fa fa-play"
              ></bbn-button>
              <bbn-button v-if="canClose"
                          @click="close"
                          title="<?=_("Close") ?>"
                          icon="fa fa-check"
              ></bbn-button>
              <bbn-button v-if="canMakeMe && !isManager"
                          @click="makeMe('managers')"
                          icon="fa fa-user-plus"
                          title="<?=_('Make me a supervisor')?>"
                          style="color: green"
              ></bbn-button>
              <bbn-button v-if="canMakeMe && !isWorker"
                          @click="makeMe('workers')"
                          icon="fa fa-user-plus"
                          title="<?=_('Make me a worker')?>"
                          style="color: orange"
              ></bbn-button>
              <bbn-button v-if="!isViewer"
                          @click="makeMe('viewers')"
                          icon="fa fa-user-plus"
                          title="<?=_('Make me a viewer')?>"
                          style="color: yellow"
              ></bbn-button>
              <bbn-button v-if="canPing"
                          @click="ping"
                          title="<?=_("Ping workers")?>"
                          icon="fa fa-hand-o-up"
              ></bbn-button>
              <bbn-button v-if="isAdded && canUnmakeMe"
                          @click="unmakeMe"
                          title="<?=_("Unfollow the task")?>"
                          icon="fa fa-user-times"
                          style="color: red"
              ></bbn-button>
            </div>
            <div v-if="isHolding">
              <bbn-button v-if="canResume"
                          @click="resume"
                          title="<?=_("Resume")?>"
                          icon="fa fa-play"
              ></bbn-button>
            </div>
            <div v-if="isClosed">
              <bbn-button v-if="canOpen"
                          @click="reopen"
                          title="<?=_("Reopen")?>"
                          icon="fa fa-hand-o-left"
              ></bbn-button>
            </div>
          </div>
        </div>
      </div>
      <span class="bbn-p"
            @click="showCommentAdder = true"
            v-if="!isClosed"
            style="margin: 1em"
      >
        <i class="fa fa-edit"></i> &nbsp; <?=_("Add a comment")?>
      </span>
      <div class="bbn-w-100 bbn-task-form-adder bbn-flex-fill"
           v-if="showCommentAdder"
      >
        <bbn-scroll>
          <div class="bbn-grid-fields">
            <div><?=_("Title")?></div>
            <bbn-input name="comment_title"
                       ref="comment_title"
                       v-model="commentTitle"
                       class="bbn-w-100"
            ></bbn-input>

            <div>
              <?=_("Comment")?>
              <br>
              <bbn-dropdown class="comment_type"
                            :source="commentTypes"
                            ref="comment_type"
                            @change="changeCommentType"
              ></bbn-dropdown>
            </div>
            <div>
              <component :is="commentType"
                         name="comment"
                         v-model="commentText"
                         class="bbn-w-100"
                         style="min-height: 200px"
              ></component>
            </div>

            <div><?=_("Files")?></div>
            <div class="bbn-task-files-container">
              <bbn-upload :save-url="root + 'actions/file/upload/' + ref"
                          :remove-url="root + 'actions/file/unupload/' + ref"
                          :localization="{select: 'Fichiers / Images'}"
              ></bbn-upload>
            </div>

            <div><?=_("Links")?></div>
            <div class="k-widget k-upload k-header">
              <div class="k-dropzone">
                <bbn-input name="link"
                           ref="link"
                           @keydown.enter.prevent="linkEnter"
                           class="bbn-w-100"
                           placeholder="<?=_("Type or paste your URL and press Enter to valid")?>"
                ></bbn-input>
              </div>
              <table class="k-upload-files bbn-task-links-container" ref="links_container">
                <tr v-for="(cl, idx) in commentLinks"
                    class="link-row"
                >
                  <td :class="{
                      'k-file': true,
                      'k-file-progress': cl.inProgress && !cl.error,
                      'k-file-success': !cl.inProgress && !cl.error,
                      'k-file-error': cl.error
                    }"
                  >
                    <div class="k-progress">
                      <table>
                        <tr>
                          <td class="bbn-task-link-image">
                            <img v-if="cl.image" :src="root + '/image/tmp/' + ref + '/' + cl.image">
                            <i v-else class="fa fa-link"> </i>
                          </td>
                          <td class="bbn-task-link-title">
                            <div>
                              <strong><a :href="cl.url" v-text="cl.title || cl.url"></a></strong>
                              <br>
                              <span v-if="cl.desc" v-text="cl.desc"></span>
                            </div>
                          </td>
                          <td class="bbn-task-link-actions">
                            <span class="k-upload-pct"> </span>
                            <bbn-button class="k-button-bare k-upload-action"
                                        style="display: inline-block;"
                                        @click="linkRemove(idx)"
                                        icon="fa fa-close"
                                        title="<?=_('Supprimer')?>"
                            >
                            </bbn-button>
                          </td>
                        </tr>
                      </table>
                    </div>
                  </td>
                </tr>
              </table>
            </div>
            <div class="bbn-grid-full bbn-vpadded">
              <bbn-button class="bbn-task-comment-button"
                          @click="addComment"
                          icon="fa fa-save"
              ><?=_('Save comment')?></bbn-button>
              <bbn-button class="bbn-task-comment-button"
                          @click="showCommentAdder = false"
                          icon="fa fa-close"
              ><?=_('Cancel')?></bbn-button>
            </div>
          </div>
        </bbn-scroll>
      </div>
      <div class="bbn-task-comments bbn-flex-fill bbn-w-100" v-if="!showCommentAdder">
        <bbn-scroll>
          <div class="bbn-w-100" style="padding: 0 1em">
            <div class="bbn-w-100 k-block"
                 v-for="n in source.notes"
                 style="margin-bottom: 1em"
            >
              <div class="k-header title">
                <div class="bbn-task-comment-author" style="margin: .2em; height: 40px">
                  <bbn-initial :user-id="n.id_user"
                               :title="userName(n.id_user)"
                  ></bbn-initial>
                </div>
                <div class="bbn-task-comment-author" style="margin-left: 5px">
                  <a class="author" v-text="userName(n.id_user)"></a>
                  <div class="metadata">
                    <div class="date" v-text="renderSince(n.creation)"></div>
                    <!--<div class="rating">
                      <i class="star icon"></i>
                      5 Faves
                    </div>-->
                  </div>
                </div>
                <div v-if="n.title"
                     class="title"
                     v-html="n.title"
                ></div>
              </div>
              <div class="bbn-w-50 bbn-iblock">
                <div v-if="n.content"
                     v-html="n.content"
                     class="bbn-padded"
                ></div>
              </div>
              <div class="bbn-w-50 bbn-iblock">
                <div v-if="hasFiles(n.medias)" class="bbn-w-100 k-block">
                  <div class="k-header bbn-c"><?=_('Files')?></div>
                  <div v-for="m in n.medias">
                    <span v-if="m.type === mediaFileType" style="margin-left: 2em">
                      <a class="media"
                         v-text="m.title"
                         @click="downloadMedia(m.id)"
                      ></a>
                    </span>
                  </div>
                </div>
                <div v-if="hasLinks(n.medias)" class="bbn-w-100 k-block">
                  <div class="k-header bbn-c"><?=_('Links')?></div>
                  <div v-for="m in n.medias">
                    <span v-if="m.type === mediaLinkType" style="margin-left: 2em">
                      <a class="media"
                         v-text="m.title"
                         @click="downloadMedia(m.id)"
                      ></a>
                    </span>
                  </div>
                </div>
              </div>


            </div>
          </div>
        </bbn-scroll>
      </div>
    </div>
  </div>

</div>