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
function vB_AJAX_BlogLatest(A){this.varname=A;this.active=null;this.noresults=0;this.which=null;this.inited=false;this.containers=new Array("latestblogs","latestcomments");this.init=function(){if(AJAX_Compatible&&(typeof vb_disable_ajax=="undefined"||vb_disable_ajax<2)){for(var I=0;I<this.containers.length;I++){var J=fetch_object(this.containers[I]).getElementsByTagName("img");if(J.length){for(var G=0;G<J.length;G++){img_alt_2_title(J[G])}}}var D=fetch_object("vb_bloglatest_latest_link");var E=fetch_object("vb_bloglatest_rating_link");var H=fetch_object("vb_bloglatest_blograting_link");var C=fetch_object("vb_bloglatest_latest_findmore");var B=fetch_object("vb_bloglatest_rating_findmore");var F=fetch_object("vb_bloglatest_blograting_findmore");if(this.active==null){this.active="latest";if(H&&H.style.display=="none"){this.active="blograting"}else{if(E&&E.style.display=="none"){this.active="rating"}}}if(H){H.varname=this.varname;H.which="blograting";if(!this.inited){YAHOO.util.Event.on("vb_bloglatest_blograting_link","click",this.load_data)}H.style.cursor=pointer_cursor;H.style.display=(this.active=="blograting")?"none":"";fetch_object("vb_bloglatest_blograting_findmore").style.display=(this.active=="blograting"&&this.noresults==0)?"":"none"}fetch_object("vb_bloglatest_blograting").style.display=(this.active!="blograting")?"none":"";if(E){E.varname=this.varname;E.which="rating";if(!this.inited){YAHOO.util.Event.on("vb_bloglatest_rating_link","click",this.load_data)}E.style.cursor=pointer_cursor;E.style.display=(this.active=="rating")?"none":"";fetch_object("vb_bloglatest_rating_findmore").style.display=(this.active=="rating"&&this.noresults==0)?"":"none"}fetch_object("vb_bloglatest_rating").style.display=(this.active!="rating")?"none":"";if(D){D.varname=this.varname;D.which="latest";if(!this.inited){YAHOO.util.Event.on("vb_bloglatest_latest_link","click",this.load_data)}D.style.cursor=pointer_cursor;D.style.display=(this.active=="latest")?"none":"";fetch_object("vb_bloglatest_latest_findmore").style.display=(this.active=="latest")?"":"none"}fetch_object("vb_bloglatest_latest").style.display=(this.active!="latest")?"none":"";this.inited=true}};this.handle_ajax_response=function(E){if(E.responseXML){fetch_object("progress_latest").style.display="none";var B=E.responseXML.getElementsByTagName("error");if(B.length){alert(B[0].firstChild.nodeValue)}else{var D=E.responseXML.getElementsByTagName("updated")[0];var C=D.getAttribute("data");this.noresults=D.getAttribute("noresults");this.active=D.getAttribute("which");if(C!=""){if(D.getAttribute("type")=="blog"){fetch_object("latestblogs").innerHTML=C}else{fetch_object("latestcomments").innerHTML=C}this.init()}}}};this.load_data=function(B){YAHOO.util.Event.stopEvent(B);fetch_object("progress_latest").style.display="";YAHOO.util.Connect.asyncRequest("POST",fetch_ajax_url("blog_ajax.php?do=loadupdated"),{success:blogLatest.handle_ajax_response,failure:vBulletin_AJAX_Error_Handler,timeout:vB_Default_Timeout,scope:blogLatest},SESSIONURL+"securitytoken="+SECURITYTOKEN+"&do=loadupdated&type=blog&which="+PHP.urlencode(this.which)+"&ajax=1");return false};this.init()};