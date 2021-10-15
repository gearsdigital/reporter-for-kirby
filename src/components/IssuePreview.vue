<template>
  <k-textarea-field v-if="data" label="Preview" :value="data" :buttons="false" disabled="true"/>
  <k-text v-else="!data">
    {{ $t('reporter.tab.preview.empty') }}
  </k-text>
</template>

<script>
export default {
  name: "IssuePreview",
  props: {
    data: String
  }
}
</script>
