/*======================================================================*\
|| #################################################################### ||
|| # 4.2.6 by vBS
|| # ---------------------------------------------------------------- # ||
|| # Copyright �2000-2018 vBulletin Solutions Inc. All Rights Reserved. ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- VBULLETIN IS NOT FREE SOFTWARE ---------------- # ||
|| #        www.vbulletin.com | www.vbulletin.com/license.html        # ||
|| #################################################################### ||
\*======================================================================*/
var vB_UserList_Highlighters=new Object();vBulletin.events.systemInit.subscribe(function(){var B,A=null;if(vBulletin.elements.vB_UserList_Highlighter){for(B=0;B<vBulletin.elements.vB_UserList_Highlighter.length;B++){A=vBulletin.elements.vB_UserList_Highlighter[B][0];vB_UserList_Highlighters[A]=new vB_UserList_Highlighter(A)}vBulletin.elements.vB_UserList_Highlighter=null}});function vB_UserList_Highlighter(E){var D,B,F,A,C;this.parentid=E;this.parent=YAHOO.util.Dom.get(E);this.items=new Array();F=this.parent.getElementsByTagName("label");if(F.length){for(D=0;D<F.length;D++){if(YAHOO.util.Dom.hasClass(F[D],"avatar_label")){A=F[D].getElementsByTagName("img");if(A.length){for(B=0;B<A.length;B++){if(A[B].id&&A[B].id.substr(0,8+this.parentid.length)==(this.parentid+"_avatar_")){C=new vB_UserList_UserObject(A[B].id.substr(8+this.parentid.length),this.parentid);this.items.push(C)}}}}}}this.check_all_checkbox=YAHOO.util.Dom.get(this.parentid+"_checkall");YAHOO.util.Event.on(this.check_all_checkbox,"click",this.check_all,this,true);this.show_avatars_checkbox=YAHOO.util.Dom.get(this.parentid+"_showavatars");YAHOO.util.Event.on(this.show_avatars_checkbox,"click",this.show_avatars,this,true);this.show_avatars()}vB_UserList_Highlighter.prototype.deactivate=function(){for(var A=0;A<this.items.length;A++){this.items[A].deactivate()}};vB_UserList_Highlighter.prototype.check_all=function(){var B,A;if(this.items.length){for(B=0;B<this.items.length;B++){this.items[B].usercheck.checked=this.check_all_checkbox.checked;this.items[B].shade_avatar()}}else{var A=this.parent.getElementsByTagName("input");for(B=0;B<A.length;B++){if(A[B].type=="checkbox"){A[B].checked=this.check_all_checkbox.checked}}}};vB_UserList_Highlighter.prototype.show_avatars=function(){if(this.items.length&&this.show_avatars_checkbox){if(this.show_avatars_checkbox.checked){YAHOO.util.Dom.replaceClass(this.parent,"userlist_hideavatars","userlist_showavatars");console.info("checked")}else{YAHOO.util.Dom.replaceClass(this.parent,"userlist_showavatars","userlist_hideavatars");console.info("not checked")}}};function vB_UserList_UserObject(A,B){this.avatar=YAHOO.util.Dom.get(B+"_avatar_"+A);YAHOO.util.Event.on(this.avatar,"click",this.avatar_click,this,true);this.usercheck=YAHOO.util.Dom.get(B+"_usercheck_"+A);if(this.usercheck.tagName=="INPUT"&&this.usercheck.getAttribute("type")=="checkbox"){YAHOO.util.Event.on(this.usercheck,"click",this.usercheck_click,this,true);this.shade_avatar()}}vB_UserList_UserObject.prototype.shade_avatar=function(){YAHOO.util.Dom.setStyle(this.avatar,"opacity",(this.usercheck.checked?1:0.25))};vB_UserList_UserObject.prototype.avatar_click=function(A){YAHOO.util.Event.stopEvent(A);if(this.usercheck.tagName=="SELECT"){this.usercheck.focus()}else{this.usercheck.checked=!this.usercheck.checked;this.shade_avatar()}};vB_UserList_UserObject.prototype.usercheck_click=function(A){this.shade_avatar()};vB_UserList_UserObject.prototype.deactivate=function(){yAHOO.util.Event.removeListener(this.avatar,"click",this.avatar_click);YAHOO.util.Event.removeListener(this.usercheck,"click",this.usercheck_click)};