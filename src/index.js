import View from "./components/View.vue";
import SectionView from "./components/SectionView.vue";

panel.plugin("gearsdigital/kirby-reporter", {
  components: {
    "k-reporter-view": View
  },
  sections: {
    reporter: SectionView
  }
});
