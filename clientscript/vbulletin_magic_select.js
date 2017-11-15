vBulletin.events.systemInit.subscribe(function(){new vB_MagicSelect_Factory()});function vB_MagicSelect_Factory(){this.controls=new Array();this.open_fieldname=null;this.ltr_mode=(document.getElementsByTagName("html")[0].getAttribute("dir").toLowerCase()=="ltr");this.init()}vB_MagicSelect_Factory.prototype.init=function(){if(vBulletin.elements.vB_MagicSelect){for(var C=0;C<vBulletin.elements.vB_MagicSelect.length;C++){var B=vBulletin.elements.vB_MagicSelect[C];if(vBulletin.ajaxurls[B[1]]){var A=vBulletin.ajaxurls[B[1]][0];var D=vBulletin.ajaxurls[B[1]][1];this.controls[B[1]]=new vB_MagicSelect(B[0],B[1],B[2],A,D,this)}}vBulletin.elements.vB_MagicSelect=null}};vB_MagicSelect_Factory.prototype.close_all=function(){if(this.open_fieldname){this.controls[this.open_fieldname].deactivate_control();this.controls[this.open_fieldname].close_menu()}else{for(var A in this.controls){if(!YAHOO.lang.hasOwnProperty(A,this.controls)){this.controls[A].deactivate_control();this.controls[A].close_menu()}}}};vB_MagicSelect_Factory.prototype.set_open_fieldname=function(A){vBulletin.console("vB_MagicSelect (Factory) :: set_open_fieldname(%s)",A);this.open_fieldname=A};function vB_MagicSelect(H,I,J,G,F,E){this.htmlelement=YAHOO.util.Dom.get(H);this.fieldname=I;this.itemid=J;this.fetchurl=G;this.saveurl=F;this.factory=E;this.selectedIndex=-1;this.menuopen=false;YAHOO.util.Dom.removeClass(this.htmlelement,"vB_MagicSelect_preload");YAHOO.util.Dom.addClass(this.htmlelement,"vB_MagicSelect");YAHOO.util.Dom.addClass(this.htmlelement,"vB_MagicSelectCursor");var M=YAHOO.util.Dom.getElementsByClassName("shade","span",this.htmlelement);if(M.length){this.labeltext=document.createTextNode(M[0].hasChildNodes()?PHP.trim(M[0].firstChild.nodeValue):"");this.htmlelement.removeChild(M[0]);this.valuetext=document.createTextNode(this.htmlelement.hasChildNodes()?PHP.trim(this.htmlelement.firstChild.nodeValue):"");while(this.htmlelement.hasChildNodes()){this.htmlelement.removeChild(this.htmlelement.firstChild)}var N=document.createElement("table");N.setAttribute("width","100%");N.setAttribute("cellPadding",0);N.setAttribute("cellSpacing",0);N.setAttribute("border",0);var D=N.appendChild(document.createElement("tbody"));var K=D.appendChild(document.createElement("tr"));var C=K.appendChild(document.createElement("td"));var L=C.appendChild(document.createElement("span"));L.className="shade";L.appendChild(this.labeltext);C.appendChild(document.createTextNode(" "));this.value_container=C.appendChild(document.createElement("span"));this.value_container.appendChild(this.valuetext);C.className="smallfont";C.style.whiteSpace="nowrap";var B=K.appendChild(document.createElement("td"));B.setAttribute("align",(this.factory.ltr_mode?"right":"left"));B.style.whiteSpace="nowrap";this.button=B.appendChild(this.create_button());this.htmlelement.appendChild(N)}else{var A=this.htmlelement.innerHTML;this.htmlelement.innerHTML="";this.value_container=this.htmlelement.appendChild(document.createElement("span"));this.value_container.innerHTML=A;this.button=this.htmlelement.appendChild(this.create_button())}YAHOO.util.Event.addListener(this.htmlelement,"mouseover",this.control_mouseover,this,true);YAHOO.util.Event.addListener(this.htmlelement,"mouseout",this.control_mouseout,this,true);YAHOO.util.Event.addListener(this.htmlelement,"click",this.control_click,this,true);YAHOO.util.Event.addListener(window,"resize",this.handle_resize,this,true)}vB_MagicSelect.prototype.create_button=function(){var A=document.createElement("img");A.src=IMGDIR_MISC+"/13x13arrowdown.gif";A.className="inlineimg vB_MagicSelect_button";A.style[(this.factory.ltr_mode?"marginLeft":"marginRight")]="2px";return A};vB_MagicSelect.prototype.create_option=function(D,A,C){var B=document.createElement("option");B.value=D;B.innerHTML=A;if(C=="yes"||C==true){B.selected=true;B.setAttribute("selected",true)}else{B.selected=false;B.removeAttribute("selected")}return B};vB_MagicSelect.prototype.populate_menu=function(H,B){vBulletin.console("vB_MagicSelect '%s' :: Populate Menu Starting (%s)",this.fieldname,(B?"Save":"Load"));if(!B){if(this.menu){return }this.menu=document.body.appendChild(document.createElement("select"));this.menu.style.position="absolute";this.menu.style.top="0px";this.menu.style.left="0px";this.menu.style.display="none";this.menu.style.zIndex=10;YAHOO.util.Event.addListener(this.menu,"click",this.menu_click,this,true);YAHOO.util.Event.addListener(this.menu,"blur",this.menu_blur,this,true);YAHOO.util.Event.addListener(this.menu,"keypress",this.menu_keypress,this,true)}else{var E=H.responseXML.getElementsByTagName("error");if(E[0]){vBulletin.console("vB_MagicSelect '%s' :: Error: %s \nRevert value to %s",this.fieldname,E[0].firstChild.nodeValue,this.menu.options[this.selectedIndex].innerHTML);alert(E[0].firstChild.nodeValue)}}while(this.menu.hasChildNodes()){this.menu.removeChild(this.menu.firstChild)}if(is_ie&&!is_ie7){this.menu.style.display="";this.menu.style.display="none"}var A=H.responseXML.getElementsByTagName("items")[0].childNodes;for(var F=0;F<A.length;F++){if(A[F].nodeType==1){if(A[F].tagName=="itemgroup"){var D=document.createElement("optgroup");D.label=A[F].getAttribute("label");var G=A[F].getElementsByTagName("item");for(var C=0;C<G.length;C++){D.appendChild(this.create_option(G[C].getAttribute("itemid"),G[C].firstChild.nodeValue,G[C].getAttribute("selected")))}this.menu.appendChild(D)}else{if(A[F].tagName=="item"){this.menu.appendChild(this.create_option(A[F].getAttribute("itemid"),A[F].firstChild.nodeValue,A[F].getAttribute("selected")))}}}}this.set_value(this.menu.selectedIndex);this.menu.setAttribute("size",Math.min((this.menu.options.length+this.menu.getElementsByTagName("optgroup").length),11));if(!B){this.open_menu()}vBulletin.console("vB_MagicSelect '%s' :: Populate Menu Completed (%s)",this.fieldname,(B?"Save":"Load"))};vB_MagicSelect.prototype.handle_resize=function(){if(this.menu){this.close_menu();this.deactivate_control();this.menu.parentElement.removeChild(this.menu);this.menu=null}};vB_MagicSelect.prototype.fetch_offset=function(C){var B=C.offsetLeft;var A=C.offsetTop;while((C=C.offsetParent)!=null){B+=C.offsetLeft;A+=C.offsetTop}return{0:B,1:A}};vB_MagicSelect.prototype.open_menu=function(){vBulletin.console("vB_MagicSelect '%s' :: open_menu()",this.fieldname);if(this.menu){this.activate_control();this.menu.style.display="";this.menu.style.width=Math.max(this.menu.offsetWidth,this.htmlelement.offsetWidth)+"px";if(is_opera&&YAHOO.env.getVersion("dom").build<=204){var D=this.htmlelement;var B=D.offsetLeft;var A=D.offsetTop;while((D=D.offsetParent)!=null){B+=D.offsetLeft;A+=D.offsetTop}var C={0:B,1:A}}else{var C=YAHOO.util.Dom.getXY(this.htmlelement)}C[1]+=this.htmlelement.offsetHeight;if(this.factory.ltr_mode){C[0]+=this.htmlelement.offsetWidth-this.menu.offsetWidth}YAHOO.util.Dom.setXY(this.menu,C);this.menu.focus();this.factory.set_open_fieldname(this.fieldname)}else{YAHOO.util.Connect.asyncRequest("POST",fetch_ajax_url(this.fetchurl[0]),{success:this.populate_menu,failure:this.request_timeout,timeout:5000,scope:this},construct_phrase(this.fetchurl[1],PHP.urlencode(this.itemid),PHP.urlencode(this.fieldname)))}return false};vB_MagicSelect.prototype.close_menu=function(){if(this.menu){this.menu.style.display="none"}this.factory.set_open_fieldname(null);return false};vB_MagicSelect.prototype.activate_control=function(){YAHOO.util.Dom.replaceClass(this.htmlelement,"vB_MagicSelect","vB_MagicSelect_hover")};vB_MagicSelect.prototype.deactivate_control=function(){YAHOO.util.Dom.replaceClass(this.htmlelement,"vB_MagicSelect_hover","vB_MagicSelect")};vB_MagicSelect.prototype.save_value=function(A){var B=this.menu.options[A];vBulletin.console("vB_MagicSelect '%s' :: save_value(%s)",this.fieldname,B.value);this.deactivate_control();this.close_menu();if(this.selectedIndex!=A&&!this.saver){this.set_temp_value(B.innerHTML);this.saver=YAHOO.util.Connect.asyncRequest("POST",fetch_ajax_url(this.saveurl[0]),{success:this.save_complete,failure:this.request_timeout,timeout:5000,scope:this},construct_phrase(this.saveurl[1],PHP.urlencode(this.itemid),PHP.urlencode(this.fieldname),PHP.urlencode(B.value),PHP.urlencode(B.innerHTML)))}};vB_MagicSelect.prototype.save_complete=function(A){vBulletin.console("vB_MagicSelect '%s' :: save_complete()",this.fieldname);this.populate_menu(A,true);this.saver=null};vB_MagicSelect.prototype.set_value=function(A){var B=this.menu.options[this.menu.selectedIndex];vBulletin.console("vB_MagicSelect '%s' :: set_value(%s) = %s",this.fieldname,A,B.innerHTML);this.selectedIndex=A;this.value_container.innerHTML=B.innerHTML;this.button.src=IMGDIR_MISC+"/13x13arrowdown.gif";YAHOO.util.Dom.removeClass(this.value_container,"shade")};vB_MagicSelect.prototype.set_temp_value=function(A){vBulletin.console("vB_MagicSelect '%s' :: set_temp_value(%s)",this.fieldname,A);this.button.src=IMGDIR_MISC+"/13x13progress.gif";this.value_container.innerHTML=A;YAHOO.util.Dom.addClass(this.value_container,"shade")};vB_MagicSelect.prototype.control_mouseover=function(A){A=do_an_e(A);if(!this.factory.open_fieldname&&!this.saver){YAHOO.util.Dom.replaceClass(this.htmlelement,"vB_MagicSelect","vB_MagicSelect_hover")}return false};vB_MagicSelect.prototype.control_mouseout=function(A){A=do_an_e(A);if(!this.factory.open_fieldname&&!this.saver){YAHOO.util.Dom.replaceClass(this.htmlelement,"vB_MagicSelect_hover","vB_MagicSelect")}return false};vB_MagicSelect.prototype.control_click=function(A){A=do_an_e(A);if(!this.saver){if(this.factory.open_fieldname){if(this.factory.open_fieldname==this.fieldname){this.factory.close_all()}else{this.factory.close_all();this.open_menu()}}else{this.open_menu()}}};vB_MagicSelect.prototype.menu_click=function(A){A=do_an_e(A);this.save_value(this.menu.selectedIndex)};vB_MagicSelect.prototype.menu_blur=function(A){A=do_an_e(A);this.save_value(this.menu.selectedIndex)};vB_MagicSelect.prototype.menu_keypress=function(A){switch(A.keyCode){case 13:this.save_value(this.menu.selectedIndex);this.close_menu();return true;case 27:this.set_value(this.selectedIndex);this.close_menu();return true;default:return true}};vB_MagicSelect.prototype.request_timeout=function(){if(typeof (vbphrase.request_timed_out_refresh)!="undefined"){alert(vbphrase.request_timed_out_refresh)}};vBulletin.console("Loaded vBulletin Magic Selects");