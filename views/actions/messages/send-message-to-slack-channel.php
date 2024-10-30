<div class="row gy-3 pt-3">
    <div class="col-12">
        <label for="webhook-url" class="d-flex align-items-center form-label">
            <?php _e('Slack Webhook URL', 'mantiq'); ?>
            <a class="ms-auto px-2 text-decoration-none small rounded-pill border border-primary" target="_blank"
               rel="noopener"
               href="https://api.slack.com/messaging/webhooks#getting_started">
                <?php _e('Get one', 'mantiq'); ?>
                <span class="material-icons">open_in_new</span></a>
        </label>

        <reference-input-helper :single="true">
            <input type="text"
                   id="webhook-url"
                   class="form-control form-control-sm"
                   placeholder="<?php _e('Slack Webhook URL...', 'mantiq'); ?>"
                   v-model="arguments.webhook_url"
                   v-variable-finder-trigger>
        </reference-input-helper>
    </div>

    <div class="col-12" :type="argument('type', 'markdown')">
        <div class="form-check form-check-inline mb-2">
            <input class="form-check-input" type="radio" name="message-type" id="markdown-message"
                   value="markdown"
                   v-model="arguments.type">
            <label class="form-check-label" for="markdown-message">
                <?php _e('Text Message', 'mantiq'); ?>
            </label>
        </div>

        <div class="form-check form-check-inline mb-2">
            <input class="form-check-input" type="radio" name="message-type" id="blocks-message"
                   value="payload"
                   v-model="arguments.type">
            <label class="form-check-label" for="blocks-message">
                <?php _e('Rich Message', 'mantiq'); ?>
            </label>
        </div>

        <template v-if="arguments.type === 'markdown'">
            <reference-input-helper>
        <textarea id="message"
                  class="form-control form-control-sm"
                  placeholder="<?php esc_attr_e('Message...', 'mantiq'); ?>"
                  rows="4"
                  v-model="arguments.content"
                  v-variable-finder-trigger></textarea>
            </reference-input-helper>
            <div class="d-flex align-items-center small mt-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="16" viewBox="0 0 208 128">
                    <rect width="198" height="118" x="5" y="5" ry="10" stroke="#000" stroke-width="10" fill="none"/>
                    <path d="M30 98V30h20l20 25 20-25h20v68H90V59L70 84 50 59v39zm125 0l-30-33h20V30h20v35h20z"/>
                </svg>
                <a href="https://www.markdownguide.org/tools/slack/"
                   target="_blank"
                   rel="noopener" class="mx-1 text-primary text-decoration-none">Markdown</a>
                is supported.
            </div>
        </template>

        <template v-else>
            <ol class="list-unstyled p-3 m-0 border border-bottom-0 bg-primary-light rounded-top">
                <li>
                    1. Open Slack's
                    <a class="text-decoration-none"
                       target="_blank"
                       rel="noopener"
                       href="https://app.slack.com/block-kit-builder">
                        <?php _e('Block Kit Builder', 'mantiq'); ?>
                        <span class="material-icons">open_in_new</span></a>
                </li>
                <li>2. Compose your message.</li>
                <li>3. Copy the payload then paste it below</li>
            </ol>

            <json-editor id="blocks" v-model="arguments.payload"></json-editor>

            <a v-if="arguments.payload"
               class="btn btn-sm btn-outline-primary mt-3"
               target="_blank"
               rel="noopener"
               :href="'https://app.slack.com/block-kit-builder#' + encodeURI(arguments.payload || '')">
                <?php _e('Edit in Block Kit Builder', 'mantiq'); ?>
                <span class="material-icons">open_in_new</span>
            </a>
        </template>
    </div>

    <div class="col-12 d-flex align-items-center pt-3">
        <small class="text-uppercase text-primary"><?php _e('Advanced users zone', 'mantiq'); ?></small>
        <div class="border-bottom border-primary flex-fill ms-3"></div>
    </div>

    <div class="col-12">
        <details :open="arguments.customArguments ? true : undefined">
            <summary class="d-flex align-items-center form-label">
                <span><?php _e('Extra arguments', 'mantiq'); ?></span>
                <span class="badge bg-primary rounded-pill ms-2 fw-normal">JSON</span>
                <a class="ms-auto text-decoration-none small" target="_blank"
                   rel="noopener"
                   href="https://api.slack.com/messaging/webhooks#advanced_message_formatting">
                    <?php _e('Docs', 'mantiq'); ?>
                    <span class="material-icons">open_in_new</span></a>
            </summary>

            <json-editor id="post-arguments" v-model="arguments.customArguments"></json-editor>
        </details>
    </div>
</div>
