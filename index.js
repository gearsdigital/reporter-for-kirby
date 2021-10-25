(function() {
  "use strict";
  var vueSlimTabs_common = {};
  /*!
   * vue-slim-tabs v0.3.0
   * (c) 2017-present egoist <0x142857@gmail.com>
   * Released under the MIT License.
   */
  Object.defineProperty(vueSlimTabs_common, "__esModule", { value: true });
  var Tabs = {
    name: "tabs",
    props: {
      defaultIndex: {
        default: 0,
        type: Number
      },
      onSelect: {
        type: Function
      }
    },
    data: function data() {
      return {
        selectedIndex: this.defaultIndex
      };
    },
    methods: {
      switchTab: function switchTab(e, index, isDisabled) {
        if (!isDisabled) {
          this.selectedIndex = index;
          this.onSelect && this.onSelect(e, index);
        }
      }
    },
    render: function render2() {
      var _this = this;
      var h = arguments[0];
      var tabs = this.$slots.default.filter(function(component) {
        return component.componentOptions;
      });
      var tabList = [];
      tabs.forEach(function(child, index) {
        var _child$componentOptio = child.componentOptions.propsData, title = _child$componentOptio.title, titleSlot = _child$componentOptio.titleSlot, disabled = _child$componentOptio.disabled;
        var content = titleSlot ? _this.$slots[titleSlot] : title;
        var isDisabled = disabled === true || disabled === "";
        tabList.push(h("li", {
          "class": "vue-tab",
          attrs: {
            role: "tab",
            "aria-selected": _this.selectedIndex === index ? "true" : "false",
            "aria-disabled": isDisabled ? "true" : "false"
          },
          on: {
            "click": function click(e) {
              return _this.switchTab(e, index, isDisabled);
            }
          }
        }, [content]));
      });
      return h("div", {
        "class": "vue-tabs",
        attrs: { role: "tabs" }
      }, [h("ul", {
        "class": "vue-tablist",
        attrs: { role: "tablist" }
      }, [this.$slots.left, tabList, this.$slots.right]), tabs[this.selectedIndex]]);
    }
  };
  var Tab = {
    name: "tab",
    props: ["title", "titleSlot", "disabled"],
    render: function render2() {
      var h = arguments[0];
      return h("div", {
        "class": "vue-tabpanel",
        attrs: { role: "tabpanel" }
      }, [this.$slots.default]);
    }
  };
  function install(Vue) {
    Vue.component(Tabs.name, Tabs);
    Vue.component(Tab.name, Tab);
  }
  var Tabs_1 = vueSlimTabs_common.Tabs = Tabs;
  var Tab_1 = vueSlimTabs_common.Tab = Tab;
  vueSlimTabs_common.install = install;
  var render$3 = function() {
    var _vm = this;
    var _h = _vm.$createElement;
    var _c = _vm._self._c || _h;
    return _vm.data ? _c("k-textarea-field", { attrs: { "label": "Preview", "value": _vm.data, "buttons": false, "disabled": "true" } }) : _c("k-text", [_vm._v(" " + _vm._s(_vm.$t("reporter.tab.preview.empty")) + " ")]);
  };
  var staticRenderFns$3 = [];
  render$3._withStripped = true;
  function normalizeComponent(scriptExports, render2, staticRenderFns2, functionalTemplate, injectStyles2, scopeId, moduleIdentifier, shadowMode) {
    var options = typeof scriptExports === "function" ? scriptExports.options : scriptExports;
    if (render2) {
      options.render = render2;
      options.staticRenderFns = staticRenderFns2;
      options._compiled = true;
    }
    if (functionalTemplate) {
      options.functional = true;
    }
    if (scopeId) {
      options._scopeId = "data-v-" + scopeId;
    }
    var hook;
    if (moduleIdentifier) {
      hook = function(context) {
        context = context || this.$vnode && this.$vnode.ssrContext || this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext;
        if (!context && typeof __VUE_SSR_CONTEXT__ !== "undefined") {
          context = __VUE_SSR_CONTEXT__;
        }
        if (injectStyles2) {
          injectStyles2.call(this, context);
        }
        if (context && context._registeredComponents) {
          context._registeredComponents.add(moduleIdentifier);
        }
      };
      options._ssrRegister = hook;
    } else if (injectStyles2) {
      hook = shadowMode ? function() {
        injectStyles2.call(this, (options.functional ? this.parent : this).$root.$options.shadowRoot);
      } : injectStyles2;
    }
    if (hook) {
      if (options.functional) {
        options._injectStyles = hook;
        var originalRender = options.render;
        options.render = function renderWithStyleInjection(h, context) {
          hook.call(context);
          return originalRender(h, context);
        };
      } else {
        var existing = options.beforeCreate;
        options.beforeCreate = existing ? [].concat(existing, hook) : [hook];
      }
    }
    return {
      exports: scriptExports,
      options
    };
  }
  const script$3 = {
    name: "IssuePreview",
    props: {
      data: String
    }
  };
  const __cssModules$3 = {};
  var __component__$3 = /* @__PURE__ */ normalizeComponent(script$3, render$3, staticRenderFns$3, false, injectStyles$3, null, null, null);
  function injectStyles$3(context) {
    for (let o in __cssModules$3) {
      this[o] = __cssModules$3[o];
    }
  }
  __component__$3.options.__file = "src/components/IssuePreview.vue";
  var IssuePreview = /* @__PURE__ */ function() {
    return __component__$3.exports;
  }();
  var render$2 = function() {
    var _vm = this;
    var _h = _vm.$createElement;
    var _c = _vm._self._c || _h;
    return _c("div", { staticClass: "k-kit-form", on: { "input": function($event) {
      return _vm.somethingChanged();
    } } }, [_vm.errors.length ? _c("k-box", { staticClass: "k-kit-form--note", attrs: { "theme": "negative" } }, [_c("k-icon", { attrs: { "type": "alert" } }), _vm._l(_vm.errors, function(error) {
      return _c("p", [_vm._v(_vm._s(error))]);
    })], 2) : _vm._e(), _vm.hasResponse ? _c("k-box", { staticClass: "k-kit-form--note", attrs: { "theme": "positive" } }, [_c("k-icon", { attrs: { "type": "check" } }), _c("p", { domProps: { "innerHTML": _vm._s(_vm.successMessage) } })], 1) : _vm._e(), _c("k-form", [_c("k-grid", { staticClass: "title-field" }, [_c("k-column", [_c("k-text-field", { attrs: { "name": "title", "label": _vm.$t("reporter.form.field.title"), "required": "" }, model: { value: _vm.issue.title, callback: function($$v) {
      _vm.$set(_vm.issue, "title", $$v);
    }, expression: "issue.title" } })], 1)], 1), _c("tabs", [_c("tab", { attrs: { "title": _vm.$t("reporter.tab.write") } }, [_c("k-fieldset", { attrs: { "fields": _vm.fields }, on: { "submit": function($event) {
      $event.preventDefault();
      return _vm.checkForm($event);
    } }, model: { value: _vm.issue.formFields, callback: function($$v) {
      _vm.$set(_vm.issue, "formFields", $$v);
    }, expression: "issue.formFields" } })], 1), _c("tab", { attrs: { "title": _vm.$t("reporter.tab.preview") } }, [_c("issue-preview", { attrs: { "data": _vm.previewData } })], 1)], 1), _c("k-line-field"), _c("k-button", { class: { "is-loading": _vm.loading }, attrs: { "disabled": _vm.loading, "icon": _vm.buttonIcon }, on: { "click": _vm.checkForm } }, [_vm._v(" " + _vm._s(_vm.$t("reporter.form.button.save")) + " ")])], 1)], 1);
  };
  var staticRenderFns$2 = [];
  render$2._withStripped = true;
  var IssueForm_vue_vue_type_style_index_0_lang = "";
  const script$2 = {
    name: "IssueForm",
    components: { IssuePreview, Tabs: Tabs_1, Tab: Tab_1 },
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
        return Object.keys(this.response).length > 1;
      },
      buttonIcon() {
        return this.loading ? "loader" : "check";
      },
      successMessage() {
        return this.$t("reporter.form.success", { issueLink: this.issueLink });
      },
      issueLink() {
        const issueLink = this.response.issueUrl;
        const issueId = this.response.issueId;
        return this.$t("reporter.form.issue.link", { issueLink, issueId });
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
          formFields: {}
        }
      };
    },
    mounted() {
      const previewTab = this.$el.querySelectorAll(".vue-tab")[1];
      previewTab.addEventListener("click", () => {
        if (this.previewClicked) {
          this.loadPreview();
        }
        this.previewClicked = true;
      });
      previewTab.addEventListener("mouseenter", () => {
        if (this.dirty) {
          this.loadPreview();
        }
      });
      const writeTab = this.$el.querySelectorAll(".vue-tab")[0];
      writeTab.addEventListener("click", () => {
        this.dirty = false;
        this.previewClicked = false;
      });
    },
    methods: {
      somethingChanged() {
        this.dirty = true;
      },
      loadPreview() {
        const request = this.$api.post("reporter/report/preview", this.issue);
        request.then((response) => {
          this.previewData = response;
          this.dirty = false;
        });
      },
      checkForm() {
        this.errors = [];
        if (!this.issue.title && this.issue.title < 3) {
          this.errors.push(this.$t("reporter.form.error.title"));
        }
        if (!this.errors.length) {
          this.submit();
        }
      },
      submit() {
        this.loading = true;
        const request = this.$api.post("reporter/report", this.issue);
        request.then((response) => {
          this.response = response;
          this.loading = false;
          this.issue = {};
        }, (reject) => {
          this.errors.push(this.$t(reject));
          this.loading = false;
          this.response = {};
        });
      }
    }
  };
  const __cssModules$2 = {};
  var __component__$2 = /* @__PURE__ */ normalizeComponent(script$2, render$2, staticRenderFns$2, false, injectStyles$2, null, null, null);
  function injectStyles$2(context) {
    for (let o in __cssModules$2) {
      this[o] = __cssModules$2[o];
    }
  }
  __component__$2.options.__file = "src/components/IssueForm.vue";
  var IssueForm = /* @__PURE__ */ function() {
    return __component__$2.exports;
  }();
  var render$1 = function() {
    var _vm = this;
    var _h = _vm.$createElement;
    var _c = _vm._self._c || _h;
    return _c("k-inside", [_c("k-view", { staticClass: "k-issue-tracker-view" }, [_c("k-header", [_vm._v(_vm._s(_vm.$t("reporter.headline")))]), _c("k-text", [_vm._v(_vm._s(_vm.$t("reporter.description")))]), _c("issue-form", { attrs: { "fields": _vm.fields } })], 1)], 1);
  };
  var staticRenderFns$1 = [];
  render$1._withStripped = true;
  const script$1 = {
    components: {
      IssueForm
    },
    props: {
      fields: Object
    },
    data() {
      return {
        fields: {}
      };
    },
    async created() {
      this.$api.get("reporter/fields").then((fields) => {
        this.fields = fields;
      });
    }
  };
  const __cssModules$1 = {};
  var __component__$1 = /* @__PURE__ */ normalizeComponent(script$1, render$1, staticRenderFns$1, false, injectStyles$1, null, null, null);
  function injectStyles$1(context) {
    for (let o in __cssModules$1) {
      this[o] = __cssModules$1[o];
    }
  }
  __component__$1.options.__file = "src/components/View.vue";
  var View = /* @__PURE__ */ function() {
    return __component__$1.exports;
  }();
  var render = function() {
    var _vm = this;
    var _h = _vm.$createElement;
    var _c = _vm._self._c || _h;
    return _c("section", [_c("header", { staticClass: "k-section-header" }, [_c("k-headline", [_vm._v(" " + _vm._s(_vm.headline) + " ")])], 1), _c("issue-form", { attrs: { "fields": _vm.fields } })], 1);
  };
  var staticRenderFns = [];
  render._withStripped = true;
  var SectionView_vue_vue_type_style_index_0_lang = "";
  const script = {
    components: { IssueForm },
    props: {
      fields: Object,
      headline: String
    },
    data() {
      return {
        fields: {}
      };
    },
    async created() {
      this.$api.get("reporter/fields").then((fields) => {
        this.fields = fields;
      });
    }
  };
  const __cssModules = {};
  var __component__ = /* @__PURE__ */ normalizeComponent(script, render, staticRenderFns, false, injectStyles, null, null, null);
  function injectStyles(context) {
    for (let o in __cssModules) {
      this[o] = __cssModules[o];
    }
  }
  __component__.options.__file = "src/components/SectionView.vue";
  var SectionView = /* @__PURE__ */ function() {
    return __component__.exports;
  }();
  panel.plugin("gearsdigital/kirby-reporter", {
    components: {
      "k-reporter-view": View
    },
    sections: {
      reporter: SectionView
    }
  });
})();
