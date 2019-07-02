<template>
  <k-view class="k-issue-tracker-view">
    <k-header>{{$t('reporter.headline')}}</k-header>
    <k-text>{{$t('reporter.description')}}</k-text>
    <issue-form :fields="fields"/>
  </k-view>
</template>

<script>
  import IssueForm from "./IssueForm";

  export default {
    components: {IssueForm},
    props: {
      fields: Object
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
