(()=>{"use strict";var e={390:(e,t,n)=>{n.d(t,{Z:()=>r});const r=Marionette.ItemView.extend({id:"elementor-template-library-templates-empty",template:"#tmpl-elementor-template-library-templates-empty",ui:{title:".elementor-template-library-blank-title",message:".elementor-template-library-blank-message"},modesStrings:{empty:{title:elementor.translate("Haven’t Saved Templates Yet?","elementor"),message:elementor.translate("This is where your templates should be. Design it. Save it. Reuse it.","elementor")},noResults:{title:elementor.translate("No Results Found","elementor"),message:elementor.translate("Please make sure your search is spelled correctly or try a different words.","elementor")}},getCurrentMode:function(){return elementor.templates.getFilter("text")?"noResults":"empty"},onRender:function(){var e=this.modesStrings[this.getCurrentMode()],t=e.message;this.ui.title.html(e.title),this.ui.message.html(t)}})},630:(e,t,n)=>{n.d(t,{Z:()=>b});var r=n(942);const o=Marionette.ItemView.extend({template:"#tmpl-elementor-template-library-header-actions",id:"elementor-template-library-header-actions",ui:{import:"#elementor-template-library-header-import",sync:"#elementor-template-library-header-sync i",save:"#elementor-template-library-header-save"},events:{"click @ui.sync":"onSyncClick"},onRender:function(){this.ui.import.remove(),this.ui.save.remove()},onSyncClick:function(){var e=this;e.ui.sync.addClass("eicon-animation-spin"),contempo.editor.templates.requestLibraryData({onUpdate:function(){e.ui.sync.removeClass("eicon-animation-spin"),$e.routes.refreshContainer("contempo-library")},forceUpdate:!0,forceSync:!0})}});var i;const a=i=Marionette.Behavior.extend({ui:{insertButton:".elementor-template-library-template-insert"},events:{"click @ui.insertButton":"onInsertButtonClick"},onInsertButtonClick:function(){var e=elementor.config.document.remoteLibrary.autoImportSettings;e||!this.view.model.get("hasPageSettings")?contempo.editor.templates.importTemplate(this.view.model,{withPageSettings:e}):i.showImportDialog(this.view.model)}},{dialog:null,showImportDialog:function(e){var t=i.getDialog();t.onConfirm=function(){contempo.editor.templates.importTemplate(e,{withPageSettings:!0})},t.onCancel=function(){contempo.editor.templates.importTemplate(e)},t.show()},initDialog:function(){i.dialog=elementorCommon.dialogsManager.createWidget("confirm",{id:"elementor-insert-template-settings-dialog",headerMessage:elementor.translate("Import Document Settings","elementor"),message:elementor.translate("Do you want to also import the document settings of the template?","elementor")+"<br>"+elementor.translate("Attention: Importing may override previous settings.","elementor"),strings:{confirm:elementor.translate("Yes","elementor"),cancel:elementor.translate("No","elementor")}})},getDialog:function(){return i.dialog||i.initDialog(),i.dialog}}),l=Marionette.ItemView.extend({className:function(){var e="elementor-template-library-template",t=this.model.get("source");return e+=" elementor-template-library-template-"+t,"remote"===t&&(e+=" elementor-template-library-template-"+this.model.get("type")),e},ui:function(){return{previewButton:".elementor-template-library-template-preview"}},events:function(){return{"click @ui.previewButton":"onPreviewButtonClick"}},behaviors:{insertTemplate:{behaviorClass:a}}}).extend({template:"#tmpl-contempo-template-library-template-remote",onPreviewButtonClick:function(){$e.route("contempo-library/preview",{model:this.model})}}),s=Marionette.CompositeView.extend({template:"#tmpl-contempo-template-library-templates",id:"elementor-template-library-templates",childViewContainer:"#elementor-template-library-templates-container",reorderOnSort:!0,emptyView:function(){return new(0,n(390).Z)},ui:{textFilter:"#elementor-template-library-filter-text",selectFilter:".elementor-template-library-filter-select",toolbar:"#elementor-template-library-toolbar",toolbarRemote:"#elementor-template-library-filter-toolbar-remote"},events:{"input @ui.textFilter":"onTextFilterInput","change @ui.selectFilter":"onSelectFilterChange"},comparators:{title:function(e){return e.get("title").toLowerCase()}},getChildView:function(e){return l},filter:function(e){var t=contempo.editor.templates.getFilterTerms(),n=!0;return jQuery.each(t,(function(t){var r=elementor.templates.getFilter(t);if(r){if(this.callback){var o=this.callback.call(e,r);return o||(n=!1),o}var i=r===e.get(t);return i||(n=!1),i}})),n},initialize:function(){this.listenTo(elementor.channels.templates,"filter:change",this._renderChildren)},addSourceData:function(){var e=this.children.isEmpty();this.$el.attr("data-template-source",e?"empty":elementor.templates.getFilter("source"))},setMasonrySkin:function(){var e=new elementorModules.utils.Masonry({container:this.$childViewContainer,items:this.$childViewContainer.children()});this.$childViewContainer.imagesLoaded(e.run.bind(e))},toggleFilterClass:function(){this.$el.toggleClass("elementor-templates-filter-active",!!elementor.templates.getFilter("text"))},setFiltersUI:function(){this.$(this.ui.selectFilter).select2({placeholder:elementor.translate("Category"),allowClear:!0,width:150})},onRender:function(){this.setFiltersUI()},onRenderCollection:function(){this.addSourceData(),this.toggleFilterClass()},onBeforeRenderEmpty:function(){this.addSourceData()},onTextFilterInput:function(){elementor.templates.setFilter("text",this.ui.textFilter.val())},onSelectFilterChange:function(e){var t=jQuery(e.currentTarget),n=t.data("elementor-filter");const r=t.val();elementor.templates.setFilter(n,r)}}),m=Marionette.ItemView.extend({template:"#tmpl-elementor-template-library-header-back",id:"elementor-template-library-header-preview-back",events:{click:"onClick"},onClick:function(){$e.routes.restoreState("contempo-library")}}),c=Marionette.ItemView.extend({template:"#tmpl-elementor-template-library-header-menu",id:"elementor-template-library-header-menu",templateHelpers:function(){return{tabs:$e.components.get("contempo-library").getTabs()}}}),p=Marionette.ItemView.extend({template:"#tmpl-contempo-template-library-header-preview",id:"elementor-template-library-header-preview",behaviors:{insertTemplate:{behaviorClass:a}}}),u=Marionette.ItemView.extend({template:"#tmpl-elementor-template-library-preview",id:"elementor-template-library-preview",ui:{iframe:"> iframe"},onRender:function(){this.ui.iframe.attr("src",this.getOption("url"))}}),d=Marionette.ItemView.extend({id:"elementor-template-library-templates-unlicensed",template:"#tmpl-contempo-template-library-templates-unlicensed"}),h=elementorModules.common.views.modal.Layout.extend({getModalOptions:function(){return{id:"contempo-template-library-modal"}},initialize(){h.__super__.initialize.apply(this,arguments),this.modalContent.on("show",this.onShow.bind(this))},getLogoOptions:function(){return{title:contempo_editor.translations["Real Estate 7 Library"],click:function(){$e.run("contempo-library/open",{toDefault:!0})}}},getTemplateActionButton:function(e){var t=Marionette.TemplateCache.get("#tmpl-elementor-template-library-insert-button");return Marionette.Renderer.render(t)},setHeaderDefaultParts:function(){var e=this.getHeaderView();e.tools.show(new o),e.menuArea.show(new c),this.showLogo()},showUnlicensedView:function(){this.modalContent.show(new d)},showTemplatesView:function(e){this.modalContent.show(new s({collection:e}))},showPreviewView:function(e){this.modalContent.show(new u({url:e.get("url")}));var t=this.getHeaderView();t.menuArea.reset(),t.tools.show(new p({model:e})),t.logoArea.show(new m)},onShow:function(e,t,n){this.modal.getElements("message").toggleClass("dialog-lightbox-message-nopadding","elementor-template-library-templates-unlicensed"===e.id)}}),g=h;class f extends elementorModules.common.ComponentModal{constructor(){super(...arguments),(0,r.Z)(this,"defaultRoutes",(()=>({import:()=>{this.manager.layout.showImportView()},preview:e=>{this.manager.layout.showPreviewView(e.model)}}))),(0,r.Z)(this,"show",(e=>{e=e||{},this.manager.modalConfig=e,!e.toDefault&&$e.routes.restoreState("contempo-library")||$e.route(this.getDefaultRoute())}))}__construct(e){super.__construct(e),this.setDefaultRoute("templates/blocks")}getNamespace(){return"contempo-library"}defaultTabs(){return{"templates/blocks":{title:contempo_editor.translations["RE7 Blocks"],filter:{type:"block",source:"remote"}},"templates/templates":{title:contempo_editor.translations["RE7 Pages"],filter:{type:"page",source:"remote"}}}}defaultCommands(){return{...super.defaultCommands(),open:e=>this.show(e)}}renderTab(e){const t=this.tabs[e],n=t.getFilter?t.getFilter():t.filter;this.manager.setScreen(n)}activateTab(e){$e.routes.saveState("contempo-library"),super.activateTab(e)}open(){return super.open(),this.manager.layout||(this.manager.layout=this.layout),this.manager.layout.setHeaderDefaultParts(),!0}close(){return!!super.close()&&(this.manager.modalConfig={},!0)}getTabsWrapperSelector(){return"#elementor-template-library-header-menu"}getModalLayout(){return g}}const y=Backbone.Model.extend({defaults:{template_id:0,relative_path:"",title:"",source:"",type:"",subtype:"",category:"",author:"",thumbnail:"",favorite:0,hasPageSettings:0,url:"",export_link:"",tags:[]}}),w=Backbone.Collection.extend({model:y}),b=new function(){this.modalConfig={};const e=this,t={};let n,r,o={},i={};this.init=function(){i={text:{callback:function(e){return e=e.toLowerCase(),this.get("title").toLowerCase().indexOf(e)>=0||_.any(this.get("tags"),(function(t){return t.toLowerCase().indexOf(e)>=0}))}},type:{},subtype:{},category:{}},this.component=$e.components.register(new f({manager:this}))},this.getTemplateTypes=function(e){return e?t[e]:t},this.registerTemplateType=function(e,n){t[e]=n},this.unlicensed=function(){e.layout.hideLoadingView(),e.layout.showUnlicensedView()},this.importTemplate=function(t){let n=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{};e.layout.showLoadingView(),e.requestTemplateContent(t.get("source"),t.get("template_id"),t.get("relative_path"),{data:{page_settings:n.withPageSettings},success:function(r){var o;r.page_settings=r.page_settings?r.page_settings:{},Array.isArray(r.page_settings)&&0===r.page_settings.length&&(r.page_settings={}),n.withPageSettings&&(r.page_settings.template="elementor_header_footer");var i=jQuery.extend({external:!0,render:null===(o=r.page_settings)||void 0===o?void 0:o.template,withPageSettings:n.withPageSettings},e.modalConfig.importOptions);e.layout.hideLoadingView(),e.layout.hideModal(),$e.run("document/elements/import",{model:t,data:r,options:i,settings:{}})},error:function(t){e.showErrorDialog(t),e.unlicensed()},complete:function(){e.layout.hideLoadingView()}})},this.requestTemplateContent=function(e,t,n,r){var o={unique_id:t,data:{source:e,edit_mode:!0,display:!0,template_id:t,relative_path:n}};return r&&jQuery.extend(!0,o,r),elementorCommon.ajax.addRequest("contempo_get_template_data",o)},this.getErrorDialog=function(){return n||(n=elementorCommon.dialogsManager.createWidget("alert",{id:"elementor-template-library-error-dialog",headerMessage:"An error occurred"})),n},this.getTemplatesCollection=function(){return r},this.getConfig=function(e){return e?o[e]?o[e]:{}:o},this.requestLibraryData=function(t){if(!r||t.forceUpdate){t.onBeforeUpdate&&t.onBeforeUpdate();var n={data:{},success:function(e){function n(e,t,n){return n.indexOf(e)===t}r=new w(e.data.templates),e.data.config&&(o=e.data.config),e.data.templates.forEach((function(e){if(e.category){const t="categories-"+e.type;o[t]||(o[t]=[]),o[t].push(e.category),o[t]=o[t].filter(n),o[t].sort()}})),t.onUpdate&&t.onUpdate()},error:function(t){e.showErrorDialog(t),e.unlicensed()}};t.forceSync&&(n.data.sync=!0),elementorCommon.ajax.addRequest("contempo_get_library_data",n)}else t.onUpdate&&t.onUpdate()},this.getFilter=function(e){return elementor.channels.templates.request("filter:"+e)},this.setFilter=function(e,t,n){elementor.channels.templates.reply("filter:"+e,t),n||elementor.channels.templates.trigger("filter:change")},this.getFilterTerms=function(e){return e?i[e]:i},this.setScreen=function(t){elementor.channels.templates.stopReplying(),e.setFilter("source",t.source,!0),e.setFilter("type",t.type,!0),e.setFilter("subtype",t.subtype,!0),e.setFilter("category",t.category,!0),e.setFilter("author",t.author,!0),e.showTemplates()},this.loadTemplates=function(t){e.requestLibraryData({onBeforeUpdate:e.layout.showLoadingView.bind(e.layout),onUpdate:function(){e.layout.hideLoadingView(),t&&t()}})},this.showTemplates=function(){e.layout.setHeaderDefaultParts(),contempo_editor.has_license?e.loadTemplates((function(){var t=e.filterTemplates();e.layout.showTemplatesView(new w(t))})):e.layout.showUnlicensedView()},this.filterTemplates=function(){const n=e.getFilter("source");return r.filter((function(e){if(n!==e.get("source"))return!1;var r=t[e.get("type")];return!r||!1!==r.showInLibrary}))},this.showErrorDialog=function(e){if("object"==typeof e){var t="";_.each(e,(function(e){t+="<div>"+e.message+".</div>"})),e=t}else e?e+=".":e="<i>&#60;"+contempo_editor.translations["The error message is empty"]+"&#62;</i>";console.log(e),jQuery(".ct_error_line").remove();const n=jQuery('<div class="ct_error_line"><div class="ct_error_line_icon"></div><div class="ct_error_line_text">'+contempo_editor.translations["We’re having trouble connecting to the library, please refresh your browser or "]+'<a href="javascript:location.reload();">'+contempo_editor.translations["click here"]+"</a>.</div></div>").appendTo(".dialog-lightbox-header");requestAnimationFrame((()=>n.addClass("ct_error_line--visible")))}}},942:(e,t,n)=>{function r(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}n.d(t,{Z:()=>r})}},t={};function n(r){var o=t[r];if(void 0!==o)return o.exports;var i=t[r]={exports:{}};return e[r](i,i.exports,n),i.exports}n.n=e=>{var t=e&&e.__esModule?()=>e.default:()=>e;return n.d(t,{a:t}),t},n.d=(e,t)=>{for(var r in t)n.o(t,r)&&!n.o(e,r)&&Object.defineProperty(e,r,{enumerable:!0,get:t[r]})},n.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),(()=>{var e=n(942);const t=window.jQuery;var r=n.n(t);const o=window.contempo||{};o.editor=new class{constructor(){(0,e.Z)(this,"initTemplateLibrary",(()=>{this.templates=n(630).Z,this.templates.init();let e="preview:loaded";(0,window.elementor.helpers.compareVersions)(window.elementor.config.version,"2.8.5",">")&&(e="document:loaded"),elementor.on(e,(function(){elementor.$previewContents.find(".contempo-add-template-button").length>0||(elementor.$previewContents.find("body").append("\n      <style>\n      .contempo-add-template-button {\n        margin-left: 9px;\n        background-color: #00b6c2;\n        float: right;\n      }\n      .contempo-add-template-button svg {\n        width: 16px;\n        fill: #ffff;\n      }\n      </style>\n        "),elementor.$previewContents.find(".elementor-add-new-section .elementor-add-template-button").after('<div\n          class="elementor-add-section-area-button contempo-add-template-button"\n          title="Add Contempo Template">\n          <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 32 32" style="enable-background:new 0 0 32 32;" xml:space="preserve"><path d="M17.1,8.1c-3.9,0-6.7,3.3-6.7,7.4v0.1c0,4.2,2.8,7.5,6.7,7.5c2.9,0,4.7-1.4,6.3-3.4l5.9,4.2c-2.6,3.7-6.2,6.4-12.4,6.4c-8,0-14.5-6.2-14.5-14.6v-0.1C2.4,7.4,8.7,1,17.1,1c5.7,0,9.4,2.4,11.9,5.9l-5.9,4.5C21.6,9.4,19.8,8.1,17.1,8.1L17.1,8.1z"/></svg>\n        </div>').find("~ .contempo-add-template-button").on("click",(function(){$e.run("contempo-library/open")})))}))})),(0,e.Z)(this,"onElementorInit",(()=>{this.initTemplateLibrary()})),r()(window).on("elementor:init",this.onElementorInit)}},window.contempo=o})()})();