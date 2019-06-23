import View from "./components/View";

panel.plugin("gearsdigital/kirby-reporter", {
  views: {
    'issue-tracker': {
      component: View,
      icon: "bolt"
    }
  }
});
