<bbn-context :source="contextSrc"
             ref="context">
  <bbn-button bbn-if="!webmailReader?.currentSelectedSource?.is_draft"
              icon="nf nf-fa-tasks"
              :label="_('Tasks')"
              :notext="true"
              :disabled="isDisabled"
              @click="getRef('context').open()"/>
</bbn-context>