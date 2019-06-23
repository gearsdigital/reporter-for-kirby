<template>
  <k-view class="k-issue-tracker-view">
    <k-header>{{$t('reporter.headline')}}</k-header>
    <k-text>{{$t('reporter.description')}}</k-text>
    <issue-form/>
  </k-view>
</template>

<script>
  import IssueForm from "./IssueForm";

  export default {
    components: {IssueForm}
  };
</script>
