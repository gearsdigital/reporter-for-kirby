import View from "./components/View";
import SectionView from "./components/SectionView";

panel.plugin("gearsdigital/kirby-reporter", {
  views: {
    reporter: {
      component: View,
      icon: "bolt"
    }
  },
  sections: {
    reporter: SectionView
  }
});
