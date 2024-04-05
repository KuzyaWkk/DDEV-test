!function(t,e){"object"==typeof exports&&"object"==typeof module?module.exports=e():"function"==typeof define&&define.amd?define([],e):"object"==typeof exports?exports.CKEditor5=e():(t.CKEditor5=t.CKEditor5||{},t.CKEditor5.button=e())}(self,(()=>(()=>{var t={"ckeditor5/src/core.js":(t,e,o)=>{t.exports=o("dll-reference CKEditor5.dll")("./src/core.js")},"ckeditor5/src/ui.js":(t,e,o)=>{t.exports=o("dll-reference CKEditor5.dll")("./src/ui.js")},"ckeditor5/src/utils.js":(t,e,o)=>{t.exports=o("dll-reference CKEditor5.dll")("./src/utils.js")},"ckeditor5/src/widget.js":(t,e,o)=>{t.exports=o("dll-reference CKEditor5.dll")("./src/widget.js")},"dll-reference CKEditor5.dll":t=>{"use strict";t.exports=CKEditor5.dll}},e={};function o(i){var n=e[i];if(void 0!==n)return n.exports;var s=e[i]={exports:{}};return t[i](s,s.exports,o),s.exports}o.d=(t,e)=>{for(var i in e)o.o(e,i)&&!o.o(t,i)&&Object.defineProperty(t,i,{enumerable:!0,get:e[i]})},o.o=(t,e)=>Object.prototype.hasOwnProperty.call(t,e);var i={};return(()=>{"use strict";o.d(i,{default:()=>h});var t=o("ckeditor5/src/core.js");class e extends t.Command{constructor(t){super(t),this.set("existingButtonSelected",!1)}execute({value:t="",color:e="",bg:o="",href:i="",classes:n=""}){const s=this.editor.model,l=s.document.selection;s.change((t=>{const n=l.getFirstRange(),r=t.createElement("linkButton",{linkButtonColor:e,linkButtonBgColor:o,linkButtonHref:i}),c=t.createElement("linkButtonContents");for(const e of n.getItems()){let o;e.is("textProxy")?o=t.createText(e.data,e.textNode.getAttributes()):e.is("element")&&(o=t.cloneElement(e)),o&&s.schema.checkChild(c,o)&&t.append(o,c)}t.append(c,r),s.insertContent(r),t.setSelection(c,"in")}))}refresh(){const t=this.editor.model,e=t.document.selection,o=e.getSelectedElement(),i=t.schema.findAllowedParent(e.getFirstPosition(),"linkButton");var n;this.isEnabled=null!==i,this.existingButtonSelected=(n=o)&&"linkButton"===n.name?o:null}}var n=o("ckeditor5/src/widget.js");class s extends t.Plugin{static get requires(){return[n.Widget]}init(){this._defineSchema(),this._defineConverters(),this.editor.commands.add("addButton",new e(this.editor))}_defineSchema(){const t=this.editor.model.schema;t.register("linkButton",{allowWhere:"$text",isObject:!0,isInline:!0,allowAttributes:["linkButtonColor","linkButtonHref","linkButtonBgColor"]}),t.register("linkButtonContents",{isLimit:!0,allowIn:"linkButton",allowContentOf:"$block"}),t.addChildCheck(((t,e)=>{if(t.endsWith("linkButtonContents")&&"linkButton"===e.name)return!1}))}_defineConverters(){const{conversion:t}=this.editor,e=this.editor.config._config.button.colors.options,o=this.editor.config._config.button.background_colors.options;t.attributeToAttribute(l("linkButtonColor",e)),t.attributeToAttribute(l("linkButtonBgColor",o)),t.for("upcast").add((t=>{t.on("element:a",((t,e,o)=>{if(o.consumable.consume(e.viewItem,{name:!0,classes:"button-configure",attributes:["href"]})){const t=o.writer.createElement("linkButton",{linkButtonHref:e.viewItem.getAttribute("href")});if(!o.safeInsert(t,e.modelCursor))return;o.convertChildren(e.viewItem,t),o.updateConversionResult(t,e)}}))})),t.for("upcast").elementToElement({model:"linkButtonContents",view:{name:"span",classes:"button-configure-contents"}}),t.for("downcast").attributeToAttribute({model:"linkButtonHref",view:"href"}),t.for("dataDowncast").elementToElement({model:"linkButton",view:{name:"a",classes:"button-configure"}}),t.for("dataDowncast").elementToElement({model:"linkButtonContents",view:{name:"span",classes:"button-configure-contents"}}),t.for("editingDowncast").elementToElement({model:"linkButton",view:(t,{writer:e})=>(0,n.toWidget)(e.createContainerElement("a",{class:"button-configure",onclick:"event.preventDefault()"},{renderUnsafeAttributes:["onclick"]}),e,{label:"button widget"})}),t.for("editingDowncast").elementToElement({model:"linkButtonContents",view:(t,{writer:e})=>(0,n.toWidgetEditable)(e.createEditableElement("span",{class:"button-configure-contents"}),e)})}}function l(t,e){const o={};for(const[t,i]of Object.entries(e))o[t]={key:"class",value:i.className};return{model:{key:t,values:Object.keys(e)},view:o}}var r=o("ckeditor5/src/ui.js"),c=o("ckeditor5/src/utils.js");class a extends r.View{constructor(e,o,i,n,s,l){super(e),this.focusTracker=new c.FocusTracker,this.keystrokes=new c.KeystrokeHandler,this.colorDropdown=this._createSelectionDropdown(e,"Color",'<?xml version="1.0" encoding="utf-8"?>\x3c!-- Uploaded to: SVG Repo, www.svgrepo.com, Generator: SVG Repo Mixer Tools --\x3e\r\n<svg width="800px" height="800px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">\r\n<path d="M14 12.5001C14 13.3285 13.3284 14.0001 12.5 14.0001C11.6716 14.0001 11 13.3285 11 12.5001C11 11.6717 11.6716 11.0001 12.5 11.0001C13.3284 11.0001 14 11.6717 14 12.5001Z" fill="#0F0F0F"/>\r\n<path d="M16.5 10.0001C17.3284 10.0001 18 9.32854 18 8.50011C18 7.67169 17.3284 7.00011 16.5 7.00011C15.6716 7.00011 15 7.67169 15 8.50011C15 9.32854 15.6716 10.0001 16.5 10.0001Z" fill="#0F0F0F"/>\r\n<path d="M13 6.50011C13 7.32854 12.3284 8.00011 11.5 8.00011C10.6716 8.00011 10 7.32854 10 6.50011C10 5.67169 10.6716 5.00011 11.5 5.00011C12.3284 5.00011 13 5.67169 13 6.50011Z" fill="#0F0F0F"/>\r\n<path d="M7.50001 12.0001C8.32844 12.0001 9.00001 11.3285 9.00001 10.5001C9.00001 9.67169 8.32844 9.00011 7.50001 9.00011C6.67158 9.00011 6.00001 9.67169 6.00001 10.5001C6.00001 11.3285 6.67158 12.0001 7.50001 12.0001Z" fill="#0F0F0F"/>\r\n<path d="M14 17.5001C14 18.3285 13.3284 19.0001 12.5 19.0001C11.6716 19.0001 11 18.3285 11 17.5001C11 16.6717 11.6716 16.0001 12.5 16.0001C13.3284 16.0001 14 16.6717 14 17.5001Z" fill="#0F0F0F"/>\r\n<path d="M7.50001 17.0001C8.32844 17.0001 9.00001 16.3285 9.00001 15.5001C9.00001 14.6717 8.32844 14.0001 7.50001 14.0001C6.67158 14.0001 6.00001 14.6717 6.00001 15.5001C6.00001 16.3285 6.67158 17.0001 7.50001 17.0001Z" fill="#0F0F0F"/>\r\n<path fill-rule="evenodd" clip-rule="evenodd" d="M11.5017 1.02215C15.4049 0.791746 19.5636 2.32444 21.8087 5.41131C22.5084 6.37324 22.8228 7.63628 22.6489 8.83154C22.471 10.054 21.7734 11.2315 20.4472 11.8945C19.6389 12.2987 18.7731 12.9466 18.2401 13.668C17.7158 14.3778 17.6139 14.9917 17.8944 15.5529C18.4231 16.6102 18.8894 17.9257 18.8106 19.1875C18.7699 19.8375 18.5828 20.4946 18.1664 21.0799C17.7488 21.6667 17.1448 22.1192 16.3714 22.4286C14.6095 23.1333 12.6279 23.1643 10.8081 22.8207C8.98579 22.4765 7.24486 21.7421 5.92656 20.8194C4.00568 19.4748 2.47455 17.6889 1.71371 15.4464C0.9504 13.1965 0.995912 10.5851 2.06024 7.65803C3.64355 3.30372 7.56248 1.25469 11.5017 1.02215ZM11.6196 3.01868C8.26589 3.21665 5.18483 4.9176 3.93984 8.34149C3.00414 10.9148 3.01388 13.0536 3.60768 14.8038C4.20395 16.5613 5.42282 18.0255 7.07347 19.1809C8.14405 19.9303 9.6169 20.5604 11.1792 20.8554C12.7442 21.151 14.3181 21.0959 15.6286 20.5716C16.308 20.2999 16.7678 19.8099 16.8145 19.0627C16.8606 18.3245 16.5769 17.3901 16.1056 16.4473C15.3639 14.9639 15.8542 13.5318 16.6315 12.4796C17.4002 11.4391 18.5455 10.6093 19.5528 10.1057C20.2266 9.76878 20.5747 9.19623 20.6697 8.54355C20.7686 7.86365 20.5831 7.12638 20.1913 6.58769C18.4364 4.17486 15.0093 2.81858 11.6196 3.01868Z" fill="#0F0F0F"/>\r\n</svg>',"color",i,s),this.bgColorDropdown=this._createSelectionDropdown(e,"Bg Color",'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">\x3c!--! Font Awesome Free 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free (Icons: CC BY 4.0, Fonts: SIL OFL 1.1, Code: MIT License) Copyright 2023 Fonticons, Inc. --\x3e<path d="M162.4 6c-1.5-3.6-5-6-8.9-6h-19c-3.9 0-7.5 2.4-8.9 6L104.9 57.7c-3.2 8-14.6 8-17.8 0L66.4 6c-1.5-3.6-5-6-8.9-6H48C21.5 0 0 21.5 0 48V224v22.4V256H9.6 374.4 384v-9.6V224 48c0-26.5-21.5-48-48-48H230.5c-3.9 0-7.5 2.4-8.9 6L200.9 57.7c-3.2 8-14.6 8-17.8 0L162.4 6zM0 288v32c0 35.3 28.7 64 64 64h64v64c0 35.3 28.7 64 64 64s64-28.7 64-64V384h64c35.3 0 64-28.7 64-64V288H0zM192 432a16 16 0 1 1 0 32 16 16 0 1 1 0-32z"/></svg>\n',"bg",n,l),this.linkInputView=this._createInput("Add Link"),this.set("color",s),this.set("bg",l),this.linkInputView.fieldView.bind("href").to(this,"href"),this.set("href",""),this.saveButtonView=this._createButton("Save",t.icons.check,"ck-button-save"),this.saveButtonView.type="submit",this.cancelButtonView=this._createButton("Cancel",t.icons.cancel,"ck-button-cancel"),this.cancelButtonView.delegate("execute").to(this,"cancel"),this.childViews=this.createCollection([this.colorDropdown,this.bgColorDropdown,this.linkInputView,this.saveButtonView,this.cancelButtonView]),this._focusCycler=new r.FocusCycler({focusables:this.childViews,focusTracker:this.focusTracker,keystrokeHandler:this.keystrokes,actions:{focusPrevious:"shift + tab",focusNext:"tab"}}),this.setTemplate({tag:"form",attributes:{class:["ck","button-configure-form"],tabindex:"-1"},children:this.childViews})}render(){super.render(),(0,r.submitHandler)({view:this}),this.childViews._items.forEach((t=>{this.focusTracker.add(t.element)})),this.keystrokes.listenTo(this.element)}destroy(){super.destroy(),this.focusTracker.destroy(),this.keystrokes.destroy()}focus(){this.linkInputView.isEnabled,this.linkInputView.focus()}_createInput(t){const e=new r.LabeledFieldView(this.locale,r.createLabeledInputText);return e.label=t,e}_createButton(t,e,o){const i=new r.ButtonView;return i.set({label:t,icon:e,tooltip:!0,class:o}),i}_createSelectionDropdown(t,e,o,i,n,s){const l=(0,r.createDropdown)(t),c=n[s];return(0,r.addToolbarToDropdown)(l,Object.entries(n).map((([e,o])=>this._createSelectableButton(t,o.label,o.icon,i,e)))),l.buttonView.set({icon:o,tooltip:t.t(e),withText:!o}),l.buttonView.bind("label").to(this,i,(e=>t.t(n[e]?n[e].label:c.label))),o===n[s].icon&&l.buttonView.bind("icon").to(this,i,(t=>n[t]?n[t].icon:c.icon)),l}_createSelectableButton(t,e,o,i,n){const s=new r.ButtonView;return s.set({label:t.t(e),icon:o,tooltip:!!o,isToggleable:!0,withText:!o}),s.bind("isOn").to(this,i,(t=>t===n)),this.listenTo(s,"execute",(()=>{this.set(i,n)})),s}}class d extends t.Plugin{static get requires(){return[r.ContextualBalloon]}static get pluginName(){return"ButtonUI"}init(){const t=this.editor,e=t.ui.componentFactory,o=t.commands.get("addButton"),i=t.editing.view.document;this._balloon=this.editor.plugins.get(r.ContextualBalloon),this.formView=this._createFormView(t.locale),this.buttonView=null,e.add("button",(t=>{const e=new r.ButtonView(t);e.label="Button",e.icon='<?xml version="1.0" encoding="utf-8"?>\r\n\x3c!-- Uploaded to: SVG Repo, www.svgrepo.com, Generator: SVG Repo Mixer Tools --\x3e\r\n<svg width="800px" height="800px" viewBox="0 0 1024 1024" fill="#000000" class="icon"  version="1.1" xmlns="http://www.w3.org/2000/svg"><path d="M613.83 642.116c-61.94 0-120.172-24.116-163.964-67.922-24.566-24.552-42.9-53.476-54.498-86.002a8.008 8.008 0 0 1 4.85-10.222 8.022 8.022 0 0 1 10.222 4.85c10.792 30.268 27.862 57.202 50.734 80.066 40.778 40.778 94.996 63.236 152.656 63.236 57.678 0 111.886-22.458 152.68-63.236l135.688-135.68c40.78-40.778 63.238-94.996 63.238-152.656 0.016-57.678-22.444-111.894-63.222-152.672-40.792-40.786-95.002-63.252-152.68-63.252-57.66 0-111.872 22.466-152.648 63.252l-135.71 135.688c-18.804 18.81-33.782 40.536-44.504 64.572-1.794 4.03-6.52 5.88-10.556 4.044a7.984 7.984 0 0 1-4.044-10.558c11.518-25.832 27.604-49.172 47.796-69.368l135.712-135.688c43.792-43.808 102.016-67.938 163.956-67.938 61.942 0 120.18 24.13 163.988 67.938 90.412 90.412 90.396 237.524-0.016 327.944l-135.688 135.682c-43.81 43.806-102.048 67.922-163.99 67.922z" fill="" /><path d="M579.908 676.052c-61.94 0-120.172-24.116-163.964-67.922-28.784-28.786-49.546-64.69-60.042-103.844a7.994 7.994 0 1 1 15.446-4.138c9.77 36.436 29.104 69.874 55.904 96.674 40.778 40.778 94.988 63.236 152.656 63.236 57.676 0 111.886-22.458 152.68-63.236l135.704-135.696a7.994 7.994 0 0 1 11.308 0 7.992 7.992 0 0 1 0 11.306l-135.704 135.698c-43.808 43.808-102.046 67.922-163.988 67.922z" fill="" /><path d="M613.83 578.064c-42.996 0-86-16.368-118.734-49.102a169.158 169.158 0 0 1-11.518-12.72 7.994 7.994 0 0 1 1.13-11.252 7.988 7.988 0 0 1 11.254 1.132 155.04 155.04 0 0 0 10.44 11.542c28.698 28.682 66.854 44.47 107.444 44.47 40.576 0 78.73-15.79 107.42-44.47l135.704-135.706c28.69-28.69 44.496-66.844 44.496-107.426 0-40.584-15.792-78.738-44.48-107.428-59.27-59.238-155.662-59.238-214.87 0l-135.712 135.688a154.174 154.174 0 0 0-19.342 23.662c-2.446 3.67-7.412 4.694-11.082 2.242a7.992 7.992 0 0 1-2.24-11.082 169.894 169.894 0 0 1 21.358-26.128l135.71-135.688c65.456-65.478 172-65.462 237.484 0 31.706 31.712 49.166 73.88 49.166 118.736 0 44.862-17.46 87.03-49.182 118.734l-135.704 135.696c-32.734 32.734-75.73 49.1-118.742 49.1z" fill="" /><path d="M828.702 429.546a7.994 7.994 0 0 1-5.654-13.65c59.222-59.246 59.222-155.64 0-214.87-59.238-59.238-155.616-59.222-214.854 0a7.994 7.994 0 0 1-11.308 0 7.994 7.994 0 0 1 0-11.308c65.47-65.462 172-65.462 237.468 0 65.47 65.47 65.47 172 0 237.486a7.968 7.968 0 0 1-5.652 2.342zM308.51 947.444h-0.008c-61.942 0-120.172-24.128-163.964-67.938-43.8-43.792-67.922-102.016-67.922-163.958 0-61.94 24.122-120.164 67.922-163.97l135.704-135.682c90.388-90.442 237.51-90.426 327.954-0.016 28.814 28.846 49.57 64.76 60.02 103.868a7.996 7.996 0 0 1-5.654 9.792c-4.264 1.046-8.652-1.39-9.792-5.654-9.73-36.406-29.064-69.844-55.88-96.698-84.196-84.172-221.164-84.172-305.336 0.016l-135.706 135.68c-40.778 40.778-63.236 95.004-63.236 152.664 0 57.662 22.458 111.872 63.236 152.648 40.78 40.794 94.988 63.254 152.658 63.254 57.668 0 111.884-22.458 152.672-63.238l135.71-135.688a218.8 218.8 0 0 0 29.206-36.248 7.958 7.958 0 0 1 11.042-2.42 8 8 0 0 1 2.42 11.042 233.1 233.1 0 0 1-31.36 38.936l-135.712 135.688c-43.804 43.808-102.034 67.922-163.974 67.922z" fill="" /><path d="M274.58 981.366c-61.942 0-120.172-24.114-163.964-67.922-43.8-43.792-67.93-102.032-67.938-163.972 0-61.94 24.122-120.164 67.922-163.97l135.704-135.682a7.994 7.994 0 0 1 11.308 0 7.994 7.994 0 0 1 0 11.308l-135.706 135.68c-40.778 40.778-63.236 95.004-63.236 152.664 0.008 57.678 22.466 111.886 63.252 152.664 40.78 40.78 94.988 63.238 152.658 63.238 57.668 0 111.876-22.458 152.656-63.238l135.696-135.688a7.994 7.994 0 0 1 11.308 0 7.994 7.994 0 0 1 0 11.308l-135.698 135.688c-43.792 43.808-102.022 67.922-163.962 67.922z" fill="" /><path d="M308.502 883.394c-43.004 0-86-16.368-118.736-49.104-65.476-65.468-65.476-172.014 0-237.484l135.706-135.68c31.704-31.712 73.872-49.18 118.726-49.18h0.008c44.854 0.008 87.024 17.468 118.742 49.18 16.524 16.532 29.206 35.866 37.654 57.466a7.984 7.984 0 0 1-4.544 10.356c-4.108 1.594-8.762-0.422-10.354-4.53-7.638-19.53-19.102-37.022-34.062-51.986-28.698-28.69-66.854-44.494-107.436-44.494-40.592 0-78.738 15.796-107.428 44.494l-135.704 135.682c-28.692 28.69-44.488 66.844-44.496 107.434 0 40.592 15.804 78.746 44.496 107.436 28.69 28.69 66.844 44.496 107.428 44.496 40.582 0 78.736-15.806 107.426-44.496l124.406-124.396a7.992 7.992 0 0 1 11.306 0 7.994 7.994 0 0 1 0 11.308l-124.404 124.394c-32.736 32.738-75.73 49.104-118.734 49.104z" fill="" /><path d="M534.336 520.85a7.956 7.956 0 0 1-6.202-2.952 154.826 154.826 0 0 0-10.424-11.544c-59.24-59.222-155.632-59.222-214.856 0a7.992 7.992 0 0 1-11.306 0 7.992 7.992 0 0 1 0-11.306c65.46-65.478 171.99-65.462 237.476 0a169.404 169.404 0 0 1 11.51 12.76 8 8 0 0 1-6.198 13.042z" fill="" /><path d="M769.806 687.406c-21.912 0-47.806-4.124-76.872-16.07a8.012 8.012 0 0 1-4.358-10.45c1.686-4.092 6.356-6.014 10.448-4.358 83.664 34.484 138.75-2.232 139.282-2.592a7.996 7.996 0 0 1 9.044 13.182c-1.72 1.188-30.38 20.288-77.544 20.288z" fill="" /><path d="M810.038 724.03c-24.176 0-45.37-15.476-52.774-38.514-9.354-29.08 6.702-60.376 35.812-69.748 13.9-4.452 42.824-7.808 67.312-7.808 39.576 0 62.114 7.7 66.97 22.894 11.072 34.39-81.244 84.352-100.33 90.49a55.346 55.346 0 0 1-16.99 2.686z m50.35-100.078c-25.316 0-51.618 3.576-62.424 7.042-20.694 6.668-32.126 28.94-25.472 49.632 6.514 20.29 29.142 32.08 49.632 25.504 30.392-9.792 94.942-55.066 90.006-70.388-1.81-5.686-18.786-11.79-51.742-11.79z" fill="" /><path d="M497.438 1023.784c-3.514 0-7.042-0.438-10.534-1.328-10.996-2.794-20.248-9.698-26.042-19.444-10.02-16.868-4.458-38.732 12.384-48.76a29.788 29.788 0 0 1 22.81-3.294 29.84 29.84 0 0 1 18.414 13.776 7.988 7.988 0 0 1-2.804 10.948 8.016 8.016 0 0 1-10.956-2.794 13.974 13.974 0 0 0-8.606-6.434 13.84 13.84 0 0 0-10.684 1.544c-9.27 5.514-12.33 17.554-6.816 26.832a26.284 26.284 0 0 0 16.234 12.134c6.856 1.718 13.978 0.704 20.044-2.904 16.632-9.886 22.124-31.47 12.23-48.104-7.116-11.962-20.094-20.896-34.72-23.894-10.542-2.124-26.69-2.14-43.588 10.198a7.992 7.992 0 0 1-9.434-12.9c16.86-12.338 36.818-16.898 56.232-12.962 18.958 3.874 35.88 15.62 45.26 31.376 14.384 24.222 6.404 55.646-17.804 70.032a42.234 42.234 0 0 1-21.62 5.978z" fill="" /><path d="M559.45 105.148c-6.108 0-12.228-0.868-18.196-2.624-17.624-5.17-32.672-17.602-40.28-33.258-11.744-24.036-1.708-53.172 22.366-64.932 9.714-4.732 20.694-5.428 30.924-1.866 10.23 3.53 18.476 10.824 23.194 20.546 8.184 16.836 1.154 37.194-15.666 45.392-14.242 6.926-31.516 0.992-38.488-13.252a8 8 0 1 1 14.376-7.028c3.092 6.34 10.746 9.028 17.118 5.894 8.886-4.334 12.604-15.11 8.278-24.012a24.26 24.26 0 0 0-14.01-12.424 24.432 24.432 0 0 0-18.708 1.124c-16.148 7.878-22.88 27.424-15.002 43.558 5.678 11.682 17.046 20.99 30.4 24.91 9.636 2.842 24.676 4.084 41.34-6.098a7.99 7.99 0 0 1 10.996 2.664 7.99 7.99 0 0 1-2.672 10.988c-11.296 6.896-23.648 10.418-35.97 10.418z" fill="" /><path d="M119.626 511.908c-36.836 0-67.282-22.926-70.82-53.32-3.17-27.534 12.862-140.874 58.232-146.182 45.244-5.24 87.03 102.5 90.116 128.988 3.888 33.806-26.2 65.158-67.064 69.906a92.114 92.114 0 0 1-10.464 0.608z m-9.316-183.698c-29.354 3.35-49.118 98.088-45.62 128.536 2.866 24.528 31.172 42.434 63.564 38.67 32.102-3.732 55.888-27.144 53.014-52.18-3.45-29.604-42.674-115.026-70.958-115.026z" fill="" /><path d="M120.362 560.012a7.99 7.99 0 0 1-7.724-10.09c14.352-52.954-1.21-139.562-1.368-140.428a7.984 7.984 0 0 1 6.426-9.3c4.38-0.842 8.52 2.086 9.3 6.428 0.68 3.694 16.368 91.076 1.07 147.488a7.984 7.984 0 0 1-7.704 5.902z" fill="" /></svg>',e.tooltip=!0,e.withText=!1,e.isToggleable=!0,this.listenTo(e,"execute",(()=>{this._showUI(o.existingButtonSelected)})),this.buttonView=e;const n=()=>{const t=o.existingButtonSelected;e.isOn=!!t};return this.listenTo(o,"change:value",n),this.listenTo(o,"change:existingButtonSelected",n),this.listenTo(i,"click",(()=>{o.existingButtonSelected&&this._showUI(o.existingButtonSelected)})),this.on("showUI",((t,e)=>{this._showUI(e)})),e.bind("isOn","isEnabled").to(o,"value","isEnabled"),e}))}_createFormView(t){const e=this.editor,o=e.config._config.button.colors.options,i=e.config._config.button.background_colors.options,n=e.config._config.button.colors.default_color,s=e.config._config.button.background_colors.default_background_color,l=e.ui.componentFactory,c=new a(t,l,o,i,n,s);return this.listenTo(c,"submit",(()=>{const t={href:c.linkInputView.fieldView.element.value,color:c.color,bg:c.bg,size:c.size,style:c.style};e.execute("addButton",t),this._hideUI()})),this.listenTo(c,"cancel",(()=>{this._hideUI()})),c.keystrokes.set("Esc",((t,e)=>{this._hideUI(),e()})),(0,r.clickOutsideHandler)({emitter:c,activator:()=>this._balloon.visibleView===c,contextElements:[this._balloon.view.element],callback:()=>this._hideUI()}),c}_showUI(t){this.buttonView.isOn=!0,this._balloon.visibleView&&this._hideUI();const e=this.editor.commands.get("addButton").value;if(this._balloon.add({view:this.formView,position:this._getBalloonPositionData()}),t){const e=t.getAttribute("linkButtonColor"),o=t.getAttribute("linkButtonHref"),i=t.getAttribute("linkButtonBgColor");this.formView.color=e,this.formView.bg=i,this.formView.linkInputView.fieldView.value=o,this.formView.linkInputView.fieldView.element.value=o,this.formView.linkInputView.fieldView.set("value",o)}e&&(this.formView.linkInputView.fieldView.value=e.link,this.formView.colorDropdown.fieldView.value=e.color,this.formView.bgColorDropdown.fieldView.value=e.bg),setTimeout((()=>{this.formView.linkInputView.fieldView.focus()}),0)}_hideUI(){this._balloon.hasView(this.formView)&&(this.formView.element.reset(),this.buttonView.isOn=!1,this._balloon.remove(this.formView),this.editor.editing.view.focus())}_getBalloonPositionData(){const t=this.editor.editing.view,e=t.document;let o=null;return o=()=>t.domConverter.viewRangeToDom(e.selection.getFirstRange()),{target:o}}}class u extends t.Plugin{static get requires(){return[s,d]}}const h={Button:u}})(),i=i.default})()));