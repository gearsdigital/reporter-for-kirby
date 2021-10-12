<template>
  <section>
    <header class="k-section-header">
      <k-headline>
        {{ headline }}
      </k-headline>
    </header>
    <issue-form :fields="fields"/>
  </section>
</template>

<script>
import IssueForm from "./IssueForm.vue";

export default {
  components: {IssueForm},
  props: {
    fields: Object,
    headline: String
  },
  data() {
    return {
      fields: {}
    }
  },
  async created() {
    this.$api.get('reporter/fields').then((fields) => {
      this.fields = fields;
    });
  },
};
</script>

<style>
.k-section-name-reporter {
}

.k-section-name-reporter .k-kit-form {
  margin-top: 0;
  padding: 1.5rem;
}
</style>
