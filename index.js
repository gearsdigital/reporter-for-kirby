(()=>{(function(){"use strict";var d={};/*!
 * vue-slim-tabs v0.3.0
 * (c) 2017-present egoist <0x142857@gmail.com>
 * Released under the MIT License.
 */Object.defineProperty(d,"__esModule",{value:!0});var _={name:"tabs",props:{defaultIndex:{default:0,type:Number},onSelect:{type:Function}},data:function(){return{selectedIndex:this.defaultIndex}},methods:{switchTab:function(s,t,r){r||(this.selectedIndex=t,this.onSelect&&this.onSelect(s,t))}},render:function(){var s=this,t=arguments[0],r=this.$slots.default.filter(function(l){return l.componentOptions}),o=[];return r.forEach(function(l,c){var u=l.componentOptions.propsData,i=u.title,a=u.titleSlot,p=u.disabled,h=a?s.$slots[a]:i,n=p===!0||p==="";o.push(t("li",{class:"vue-tab",attrs:{role:"tab","aria-selected":s.selectedIndex===c?"true":"false","aria-disabled":n?"true":"false"},on:{click:function(v){return s.switchTab(v,c,n)}}},[h]))}),t("div",{class:"vue-tabs",attrs:{role:"tabs"}},[t("ul",{class:"vue-tablist",attrs:{role:"tablist"}},[this.$slots.left,o,this.$slots.right]),r[this.selectedIndex]])}},m={name:"tab",props:["title","titleSlot","disabled"],render:function(){var s=arguments[0];return s("div",{class:"vue-tabpanel",attrs:{role:"tabpanel"}},[this.$slots.default])}};function C(e){e.component(_.name,_),e.component(m.name,m)}var S=d.Tabs=_,T=d.Tab=m;d.install=C;var F=function(){var e=this,s=e.$createElement,t=e._self._c||s;return e.data?t("k-textarea-field",{attrs:{label:"Preview",value:e.data,buttons:!1,disabled:"true"}}):t("k-text",[e._v(" "+e._s(e.$t("reporter.tab.preview.empty"))+" ")])},I=[];function f(e,s,t,r,o,l,c,u){var i=typeof e=="function"?e.options:e;s&&(i.render=s,i.staticRenderFns=t,i._compiled=!0),r&&(i.functional=!0),l&&(i._scopeId="data-v-"+l);var a;if(c?(a=function(n){n=n||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext,!n&&typeof __VUE_SSR_CONTEXT__!="undefined"&&(n=__VUE_SSR_CONTEXT__),o&&o.call(this,n),n&&n._registeredComponents&&n._registeredComponents.add(c)},i._ssrRegister=a):o&&(a=u?function(){o.call(this,(i.functional?this.parent:this).$root.$options.shadowRoot)}:o),a)if(i.functional){i._injectStyles=a;var p=i.render;i.render=function(y,v){return a.call(v),p(y,v)}}else{var h=i.beforeCreate;i.beforeCreate=h?[].concat(h,a):[a]}return{exports:e,options:i}}const x={name:"IssuePreview",props:{data:String}},b={};var L=f(x,F,I,!1,R,null,null,null);function R(e){for(let s in b)this[s]=b[s]}var O=function(){return L.exports}(),E=function(){var e=this,s=e.$createElement,t=e._self._c||s;return t("div",{staticClass:"k-kit-form",on:{input:function(r){return e.somethingChanged()}}},[e.errors.length?t("k-box",{staticClass:"k-kit-form--note",attrs:{theme:"negative"}},[t("k-icon",{attrs:{type:"alert"}}),e._l(e.errors,function(r){return t("p",[e._v(e._s(r))])})],2):e._e(),e.hasResponse?t("k-box",{staticClass:"k-kit-form--note",attrs:{theme:"positive"}},[t("k-icon",{attrs:{type:"check"}}),t("p",{domProps:{innerHTML:e._s(e.successMessage)}})],1):e._e(),t("k-form",[t("k-grid",{staticClass:"title-field"},[t("k-column",[t("k-text-field",{attrs:{name:"title",label:e.$t("reporter.form.field.title"),required:""},model:{value:e.issue.title,callback:function(r){e.$set(e.issue,"title",r)},expression:"issue.title"}})],1)],1),t("tabs",[t("tab",{attrs:{title:e.$t("reporter.tab.write")}},[t("k-fieldset",{attrs:{fields:e.fields},on:{submit:function(r){return r.preventDefault(),e.checkForm(r)}},model:{value:e.issue.formFields,callback:function(r){e.$set(e.issue,"formFields",r)},expression:"issue.formFields"}})],1),t("tab",{attrs:{title:e.$t("reporter.tab.preview")}},[t("issue-preview",{attrs:{data:e.previewData}})],1)],1),t("k-line-field"),t("k-button",{class:{"is-loading":e.loading},attrs:{disabled:e.loading,icon:e.buttonIcon},on:{click:e.checkForm}},[e._v(" "+e._s(e.$t("reporter.form.button.save"))+" ")])],1)],1)},M=[],K="";const P={name:"IssueForm",components:{IssuePreview:O,Tabs:S,Tab:T},props:{fields:{type:Object},previewData:{type:String}},computed:{hasResponse(){return Object.keys(this.response).length>1},buttonIcon(){return this.loading?"loader":"check"},successMessage(){return console.log(this.issueLink),this.issueLink?this.$t("reporter.form.success",{issueLink:this.issueLink}):this.$t("reporter.form.mail.success")},issueLink(){const e=this.response.issueUrl,s=this.response.issueId;return s?this.$t("reporter.form.issue.link",{issueLink:e,issueId:s}):null}},data(){return{errors:[],response:{},loading:!1,previewClicked:!1,dirty:!0,previewData:{},issue:{title:null,formFields:{}}}},mounted(){const e=this.$el.querySelectorAll(".vue-tab")[1];e.addEventListener("click",()=>{this.previewClicked&&this.loadPreview(),this.previewClicked=!0}),e.addEventListener("mouseenter",()=>{this.dirty&&this.loadPreview()}),this.$el.querySelectorAll(".vue-tab")[0].addEventListener("click",()=>{this.dirty=!1,this.previewClicked=!1})},methods:{somethingChanged(){this.dirty=!0},loadPreview(){this.$api.post("reporter/report/preview",this.issue).then(s=>{this.previewData=s,this.dirty=!1})},checkForm(){this.errors=[],!this.issue.title&&this.issue.title<3&&this.errors.push(this.$t("reporter.form.error.title")),this.errors.length||this.submit()},submit(){this.loading=!0,this.$api.post("reporter/report",this.issue).then(s=>{this.response=s,this.loading=!1,this.issue={}},s=>{this.errors.push(this.$t(s)),this.loading=!1,this.response={}})}}},k={};var j=f(P,E,M,!1,q,null,null,null);function q(e){for(let s in k)this[s]=k[s]}var $=function(){return j.exports}(),D=function(){var e=this,s=e.$createElement,t=e._self._c||s;return t("k-inside",[t("k-view",{staticClass:"k-issue-tracker-view"},[t("k-header",[e._v(e._s(e.$t("reporter.headline")))]),t("k-text",[e._v(e._s(e.$t("reporter.description")))]),t("issue-form",{attrs:{fields:e.fields}})],1)],1)},V=[];const N={components:{IssueForm:$},props:{fields:Object},data(){return{fields:{}}},async created(){this.$api.get("reporter/fields").then(e=>{this.fields=e})}},g={};var U=f(N,D,V,!1,A,null,null,null);function A(e){for(let s in g)this[s]=g[s]}var X=function(){return U.exports}(),z=function(){var e=this,s=e.$createElement,t=e._self._c||s;return t("section",[t("header",{staticClass:"k-section-header"},[t("k-headline",[e._v(" "+e._s(e.headline)+" ")])],1),t("issue-form",{attrs:{fields:e.fields}})],1)},H=[],Q="";const W={components:{IssueForm:$},props:{fields:Object,headline:String},data(){return{fields:{}}},async created(){this.$api.get("reporter/fields").then(e=>{this.fields=e})}},w={};var B=f(W,z,H,!1,G,null,null,null);function G(e){for(let s in w)this[s]=w[s]}var J=function(){return B.exports}();panel.plugin("gearsdigital/kirby-reporter",{components:{"k-reporter-view":X},sections:{reporter:J}})})();})();
