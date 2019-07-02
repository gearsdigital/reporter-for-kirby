<template>
  <div class="k-kit-form">
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

      <k-fieldset :fields="fields" @submit.prevent="checkForm" v-model="issue.formFields"/>

      <k-line-field/>

      <k-button :class="{ 'is-loading': loading }" :disabled="loading" :icon="buttonIcon" @click="checkForm">{{$t('reporter.form.button.save')}}</k-button>
    </k-form>

  </div>
</template>

<script>
  export default {
    name: "IssueForm",
    props: {
      fields: Object
    },
    data() {
      return {
        errors: [],
        response: {},
        loading: false,
        issue: {
          title: null,
          formFields: {},
        }
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
    methods: {
      isLoading(flag) {
        this.loading = flag;
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

        request.then(response => {
          if (response.status >= 200 && response.status < 300) {
            this.response = response;
            this.issue = {};
            this.$store.dispatch("notification/success", ":)");
          } else if (response.status === 401) {
            this.errors.push(this.$t('reporter.form.error.authFailed'));
            this.response = {};
          } else if (response.status === 400) {
            this.errors.push(response.message);
            this.response = {};
          } else if (response.status === 404) {
            this.errors.push(this.$t('reporter.form.error.repoNotFound'));
            this.response = {};
          }
          // 501 is defined by this appplication -> CreateVendor.php:70
          else if (response.status === 501) {
            this.errors.push(this.$t('reporter.form.error.platform.unsupported'));
            this.response = {};
          } else {
            this.response = {};
          }

          this.loading = false;
        });

        request.catch((e) => {
          this.errors.push(this.$t(e.message));
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
      -webkit-box-shadow: rgba(66, 113, 174, .25) 0 0 0 2px;
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
</style>
