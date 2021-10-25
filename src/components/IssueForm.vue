<template>
  <div class="k-kit-form" @input="somethingChanged()">

    <k-box class="k-kit-form--note" theme="negative" v-if="errors.length">
      <k-icon type="alert"/>
      <p v-for="error in errors">{{ error }}</p>
    </k-box>

    <k-box class="k-kit-form--note" theme="positive" v-if="hasResponse">
      <k-icon type="check"/>
      <p v-html="successMessage"/>
    </k-box>

    <k-form>
      <k-grid class="title-field">
        <k-column>
          <k-text-field v-model="issue.title" name="title" :label="$t('reporter.form.field.title')" required/>
        </k-column>
      </k-grid>

      <tabs>
        <tab :title="$t('reporter.tab.write')">
          <k-fieldset :fields="fields" @submit.prevent="checkForm" v-model="issue.formFields"/>
        </tab>
        <tab :title="$t('reporter.tab.preview')">
          <issue-preview :data="previewData"/>
        </tab>
      </tabs>

      <k-line-field/>

      <k-button :class="{ 'is-loading': loading }" :disabled="loading" :icon="buttonIcon" @click="checkForm">
        {{ $t('reporter.form.button.save') }}
      </k-button>
    </k-form>

  </div>
</template>

<script>
import {Tab, Tabs} from 'vue-slim-tabs'
import IssuePreview from "./IssuePreview.vue";

export default {
  name: "IssueForm",
  components: {IssuePreview, Tabs, Tab},
  props: {
    fields: {
      type: Object
    },
    previewData: {
      type: String
    }
  },
  computed: {
    hasResponse() {
      return Object.keys(this.response).length > 1
    },
    buttonIcon() {
      return this.loading ? 'loader' : "check";
    },
    successMessage() {
      return this.$t('reporter.form.success', {issueLink: this.issueLink})
    },
    issueLink() {
      const issueLink = this.response.issueUrl;
      const issueId = this.response.issueId;

      return this.$t('reporter.form.issue.link', {issueLink, issueId})
    }
  },
  data() {
    return {
      errors: [],
      response: {},
      loading: false,
      previewClicked: false,
      dirty: true,
      previewData: {},
      issue: {
        title: null,
        formFields: {},
      }
    }
  },
  mounted() {
    const previewTab = this.$el.querySelectorAll('.vue-tab')[1];
    previewTab.addEventListener('click', () => {
      if (this.previewClicked) {
        this.loadPreview();
      }
      this.previewClicked = true;
    });

    previewTab.addEventListener('mouseenter', () => {
      if (this.dirty) {
        this.loadPreview();
      }
    });

    const writeTab = this.$el.querySelectorAll('.vue-tab')[0];
    writeTab.addEventListener('click', () => {
      this.dirty = false;
      this.previewClicked = false;
    });
  },
  methods: {
    somethingChanged() {
      this.dirty = true;
    },
    loadPreview() {
      const request = this.$api.post('reporter/report/preview', this.issue);
      request.then(response => {
        this.previewData = response;
        this.dirty = false;
      });
    },
    checkForm() {
      this.errors = [];

      if (!this.issue.title && this.issue.title < 3) {
        this.errors.push(this.$t('reporter.form.error.title'));
      }
      if (!this.errors.length) {
        this.submit();
      }
    },
    submit() {
      this.loading = true;

      const request = this.$api.post('reporter/report', this.issue);

      request.then(
        response => {
          this.response = response;
          this.loading = false;
          this.issue = {};
        },
        reject => {
          this.errors.push(this.$t(reject));
          this.loading = false;
          this.response = {};
        });
    }
  }
}
</script>

<style lang="scss">
@keyframes spin {
  100% {
    transform: rotate(360deg);
  }
}

.k-line-field {
  margin: 2rem 0 1rem;
}

.k-kit-form {
  padding: 2rem;
  margin-top: 2rem;
  margin-bottom: 2rem;
  border: 1px solid rgba(0, 0, 0, .1);
  background: #fafafa;

  .title-field {
    margin-bottom: 2.25rem;
  }

  .k-input[data-theme=field][data-invalid] {
    border: 1px solid #ccc;
    box-shadow: none
  }

  .k-input[data-theme=field][data-invalid]:focus-within {
    border: 1px solid #4271ae;
    box-shadow: 0 0 0 2px rgba(66, 113, 174, .25)
  }

  textarea {
    min-height: 120px;
  }

  &--note[data-theme] {
    margin-bottom: 1rem;
    padding-left: 3rem;
    position: relative;

    a {
      border-bottom: 1px solid rgba(0, 0, 0, 0.3);
    }

    .k-icon {
      position: absolute;
      margin-left: -2rem;
      height: 100%;
      top: 0;
    }
  }

  .k-button .k-icon-loader {
    animation: spin .8s linear infinite;
  }

  .issue-link {
    margin-left: auto;
  }
}

.vue-tablist {
  border-bottom: 1px solid #ccc;
  display: flex;
  margin-bottom: 1.25rem;
}

.vue-tab {
  display: block;
  cursor: pointer;
  padding: .7rem 1rem .5rem 1rem;
  color: #737578;
  position: relative;
  margin-bottom: -1px;

  &[aria-selected="true"] {
    color: #16171a;
    background: #fafafa;
    border-bottom: 0;


    &:after {
      position: absolute;
      content: "";
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      border-radius: 4px 4px 0 0;
      border: 1px solid #ccc;
      border-bottom: 0;
    }
  }
}
</style>
