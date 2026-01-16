<bbn-button bbn-if="!reader?.currentSelectedSource?.is_draft"
                    icon="nf nf-fa-bug"
                    :label="_('Transform in task')"
                    :notext="true"
                    @click="mailToTask"
                    :disabled="!reader?.currentSelectedSource || !!reader.currentSelectedSource.is_draft"/>