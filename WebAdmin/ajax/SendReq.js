var timerId;

function appendData(data_str, parent_node, mode)
{
    appendData.js_store='';

    data_str =data_str.replace(/<script.*?>((.|[\r\n])*?)<\/script>/ig,appendData.handle);
    //?????‚?°???»???µ?? ?????»???‡?????€???????? ?‚?µ?????‚ ?? ???µ?‚???°????
    if(parent_node)
      if (mode == 'append') {
        parent_node.innerHTML += data_str;
      } else {
        parent_node.innerHTML = data_str;
      }
    if(!!appendData.js_store)
    try{
       // alert(appendData.js_store) ;
        if(window.execScript) {
            window.execScript(appendData.js_store, "javascript");
        } else {
            eval.call(window, appendData.js_store)
        }
    } catch(e){}; // ?? ?†?µ?»???… ???‚?»?°?????? - ?±?»???? ?????¶???? ???±???°?‚??!!!!
}
appendData.handle=function($0,$1)
{
    appendData.js_store+='\n'+$1; // ?????±?????°?µ?? JS ?? ?µ???????? ?????‡??
    return '';
}


function SendReq (SendData, overlay_txt='', is_hide = true) {
		    var dpar1 = '<? echo time(); ?>';
    if(window.XMLHttpRequest) {
        try { req = new XMLHttpRequest(); }
        catch (e){}}
    else if(window.ActiveXObject) {
        try { req = new ActiveXObject('Msxml2.XMLHTTP'); }
        catch (e){ req = new ActiveXObject('Microsoft.XMLHTTP'); }}
    req.onreadystatechange = function() {
        if(req.readyState == 4) {

			SendReq_Response (req.responseText, SendData.cmd, overlay_txt);



			}


			}


			if (is_hide==true)
			{
document.getElementById("overlay_text_info").innerHTML = overlay_txt;
document.getElementById("overlay").style.display = "block";
			}
			
			SendData.lng = CUR_LNG;

GLOBAL_DATA.SendData = JSON.stringify(SendData);

//console.log("SendData: " + JSON.stringify(SendData));

req.open("POST", "QueryProcessing/"+SendData.cmd+".php", true);

    req.setRequestHeader('Content-Type', 'application/json');
    req.send(JSON.stringify(SendData));
			
	timerId = setTimeout(function(){is_stop = true;  req.abort();}, GLOBAL_DATA.TimeOut*1000);
	

}
function SendReq_Response (x, y, z) {
	
		//console.log("Get Response: " + x);
	//	alert (y);
	//alert (is_stop);
			
	if (x.trim()=="")
	{
		
			$.toast({
    heading: LOCALIZATION [CUR_LNG].ERROR_TITLE,
    text: LOCALIZATION [CUR_LNG].EMPTY_RESPONCE,
    showHideTransition: 'plain',
	hideAfter: 5000,
	stack: 4,
	position: 'bottom-right',
    icon: 'error'
});	
document.getElementById("overlay").style.display = "none";
document.getElementById("overlay_text_info").innerHTML = "";
	
	clearTimeout(timerId);
		
	return;	
	}
	
		if (is_stop==true)
		{
	$.toast({
    heading: LOCALIZATION [CUR_LNG].ERROR_TITLE,
    text: LOCALIZATION [CUR_LNG].SERVER_IS_DOWN,
    showHideTransition: 'plain',
	hideAfter: 5000,
	stack: 4,
	position: 'bottom-right',
    icon: 'error'
});	
document.getElementById("overlay").style.display = "none";
document.getElementById("overlay_text_info").innerHTML = "";
	
	clearTimeout(timerId);
	
	return;
		}
	

	
	//if (x=="") return;
	//alert (x);
	document.getElementById("overlay").style.display = "none";
	
	clearTimeout(timerId);
	
		
	if (y=="LoadPage")
	{
		appendData(x, document.getElementById("page_load"));
		$('select').select2();
		return;
	}
	
		if (y=="GetCmpBall")
	{
		
		appendData(x, document.getElementById('CntResult'));
		
		return;
	}
		
	 try {
	var Result = JSON.parse(x);
	
	if (Result.code!=0)
	{
		
		$.toast({
    heading: LOCALIZATION [CUR_LNG].ERROR_TITLE,
    text: (Result.msg),
    showHideTransition: 'plain',
	hideAfter: 5000,
	stack: 4,
	position: 'bottom-right',
    icon: 'error'
});	
		
		
		
		if (GLOBAL_DATA.IsBlock==true)
		{
			if (GLOBAL_DATA.MainColor=="")
		GLOBAL_DATA.MainColor = computedStyle(GLOBAL_DATA.ActiveBtn, 'backgroundColor');

		GLOBAL_DATA.ActiveBtn.style.backgroundColor = GLOBAL_DATA.DisabledColor;
		GLOBAL_DATA.ActiveBtn.disabled = true;
		}
		
		
		if (GLOBAL_DATA.ReqRepeat>0)
		{
			
					var ReSendToast = $.toast({
    heading: LOCALIZATION [CUR_LNG].Repeat_TITLE,
    text: (LOCALIZATION [CUR_LNG].Repeat_TXT),
    showHideTransition: 'plain',
	hideAfter: GLOBAL_DATA.ReqRepeat*1000,
	stack: 4,
	position: 'bottom-right',
    icon: 'info',	
	allowToastClose: false,
	afterHidden: function () {
        SendReq(GLOBAL_DATA.SendData);
		ReSendToast.reset();
    }
			});	
		}
		 
		
	}
	else
	{
		
		try {
		
		GLOBAL_DATA.ActiveBtn.style.backgroundColor = GLOBAL_DATA.MainColor;
		GLOBAL_DATA.ActiveBtn.disabled = false;
		
		GLOBAL_DATA.ActiveBtn.style.backgroundColor = GLOBAL_DATA.MainColor;
		}
		catch (e1) {}
		
	GLOBAL_DATA.IsBlock = false;
	GLOBAL_DATA.ReqRepeat = 0;
	
	var pagename = get_pagename();
	
		if (y=="EditBalance")
	{
		GetCmpBall();
		
		return;
	}
	
	Result.cmd = y;
	
	if (pagename=='' || pagename=='index.php')
		window.location.href = "dashboard.php";
	else
		isLoad(Result);		
	


		
	}
	
	} catch(e) {
        
		console.log(e);
		
		if (GLOBAL_DATA.IsBlock==true)
		{
			
			if (GLOBAL_DATA.MainColor=="")
		GLOBAL_DATA.MainColor = computedStyle(GLOBAL_DATA.ActiveBtn, 'backgroundColor');

		GLOBAL_DATA.ActiveBtn.style.backgroundColor = GLOBAL_DATA.DisabledColor;
		GLOBAL_DATA.ActiveBtn.disabled = true;
		}
		
		$.toast({
    heading: LOCALIZATION [CUR_LNG].ERROR_TITLE,
    text: (LOCALIZATION [CUR_LNG].ERROR_PARSER),
    showHideTransition: 'plain',
	hideAfter: 5000,
	stack: 4,
	position: 'bottom-right',
    icon: 'error'
	});	
	
	
			if (GLOBAL_DATA.ReqRepeat>0)
		{
			
					var ReSendToast = $.toast({
    heading: LOCALIZATION [CUR_LNG].Repeat_TITLE,
    text: (LOCALIZATION [CUR_LNG].Repeat_TXT),
    showHideTransition: 'plain',
	hideAfter: GLOBAL_DATA.ReqRepeat*1000,
	stack: 4,
	position: 'bottom-right',
	allowToastClose: false,
    icon: 'info',
	afterHidden: function () {
		SendReq(GLOBAL_DATA.SendData);
		ReSendToast.reset();
    }
			});	
		}
	

	}
	
	

}


