/*======================================================================*\
|| #################################################################### ||
|| # vBulletin 4.2.5
|| # ---------------------------------------------------------------- # ||
|| # Copyright �2000-2017 vBulletin Solutions Inc. All Rights Reserved. ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- VBULLETIN IS NOT FREE SOFTWARE ---------------- # ||
|| #        www.vbulletin.com | www.vbulletin.com/license.html        # ||
|| #################################################################### ||
\*======================================================================*/
function vB_ImageUpload(D,C){this.editor=C;this.ready=false;this.uploadform=YAHOO.util.Dom.get(D+"_dialog");this.events={complete:new YAHOO.util.CustomEvent("complete",this),uploaddone:new YAHOO.util.CustomEvent("uploaddone",this)};var A={"do":"manageattach",upload:1,ajax:1,flash:1,poststarttime:vBulletin.attachinfo.poststarttime,posthash:vBulletin.attachinfo.posthash,contenttypeid:vBulletin.attachinfo.contenttypeid,userid:vBulletin.attachinfo.userid,securitytoken:SECURITYTOKEN};for(var B in vBulletin.attachinfo.values){A["values["+B+"]"]=vBulletin.attachinfo.values[B]}this.yuiupload=new vB_YUI_Upload(vBulletin.attachinfo.auth_type,vBulletin.attachinfo.asset_enable,fetch_ajax_url("newattachment.php"),A,"attachment[]",D);if(vBulletin.attachinfo.asset_enable==1&&this.yuiupload.uploader&&PHP.trim(YAHOO.util.Dom.get("yui_selectfiles").innerHTML)!=""){YAHOO.util.Dom.addClass("fileupload","hidden");YAHOO.util.Dom.removeClass("upload_controls_container","hidden");this.ready=true;this.yuiupload.events.ready.subscribe(this.yuiuploadevent_ready,this);this.extensions={jpg:3024000,jpeg:3024000,gif:3024000,png:3024000};this.yuiupload.events.handleresponse.subscribe(this.yuiuploadevent_handleresponse,this);this.yuiupload.events.uploaddone.subscribe(this.yuiuploadevent_uploaddone,this);YAHOO.util.Event.on("yui_basicupload","click",this.show_basic_upload,this,true)}else{YAHOO.util.Event.purgeElement("uploadbutton",false,"click");YAHOO.util.Event.on("uploadbutton","click",this.submitupload,this,true);YAHOO.util.Dom.removeClass("fileupload","hidden")}}vB_ImageUpload.prototype.show_basic_upload=function(A){this.yuiupload.uploader=false;YAHOO.util.Dom.addClass("upload_controls_container","hidden");YAHOO.util.Dom.removeClass("fileupload","hidden");YAHOO.util.Event.purgeElement("uploadbutton",false,"click");YAHOO.util.Event.on("uploadbutton","click",this.submitupload,this,true)};vB_ImageUpload.prototype.submitupload=function(D){YAHOO.util.Event.stopEvent(D);var C=YAHOO.util.Dom.get("fileupload1");if(C.value!=""){var E={upload:this.uploadasset,failure:function(F){alert(vbphrase.upload_failed)},scope:this};this.uploadform.upload.value=1;this.uploadform.flash.value=0;this.uploadform.contenttypeid.value=this.editor.config.vbulletin.attachinfo.contenttypeid;this.uploadform.posthash.value=this.editor.config.vbulletin.attachinfo.posthash;this.uploadform.poststarttime.value=this.editor.config.vbulletin.attachinfo.poststarttime;for(var B in this.editor.config.vbulletin.attachinfo.values){var A=document.createElement("input");A.name="values["+B+"]";A.value=this.editor.config.vbulletin.attachinfo.values[B];A.type="hidden";this.uploadform.appendChild(A)}YAHOO.util.Connect.setForm(this.uploadform,true,true);YAHOO.util.Connect.asyncRequest("POST",fetch_ajax_url("newattachment.php"),E,"ajax=1")}};vB_ImageUpload.prototype.uploadasset=function(F){YAHOO.util.Dom.get("fileupload1").value="";if(F.responseXML){var G="";var B=F.responseXML.getElementsByTagName("error");if(B.length){for(var C=0;C<B.length;C++){G+=B[C].firstChild.nodeValue;if(C<B.length){G+="\r\n"}}alert(G);return }var E=F.responseXML.getElementsByTagName("uploaderror");if(E.length){for(var C=0;C<E.length;C++){G+=E[C].firstChild.nodeValue;if(C<E.length){G+="\r\n"}}alert(G);return }var A=F.responseXML.getElementsByTagName("attachmentid");var H=F.responseXML.getElementsByTagName("hasthumbnail");var D=F.responseXML.getElementsByTagName("new");if(A.length){for(var C=0;C<A.length;C++){if(D[C].firstChild.nodeValue==1){this.events.uploaddone.fire(A[C].firstChild.nodeValue,vBulletin.attachinfo.contenttypeid,H[C].firstChild.nodeValue)}}}}this.events.complete.fire()};vB_ImageUpload.prototype.yuiuploadevent_ready=function(B,A,C){C.yuiupload.setvars(C.extensions,vbphrase,vBulletin.attachinfo.attachlimit,vBulletin.attachinfo.max_file_size);YAHOO.util.Event.onAvailable("yui_selectfiles",function(){C.yuiupload.moveflashobj()})};vB_ImageUpload.prototype.yuiuploadevent_uploaddone=function(C,A,D){var B=A[0];var E=A[1];D.events.uploaddone.fire(B,vBulletin.attachinfo.contenttypeid,E)};vB_ImageUpload.prototype.yuiuploadevent_handleresponse=function(B,A,C){C.events.complete.fire()};