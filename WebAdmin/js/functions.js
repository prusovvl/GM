let version;
let ServerIP='';
let ServerPort=0;
let is_stop = false;
let tel_num_prefix='38';
let get_sign_key = false;

let GLOBAL_DATA = {ActiveBtn:'', IsBlock:false, ReqRepeat:0, DisabledColor:'#b9b9b9', MainColor:'', SendData:'', TimeOut:30};
let CUR_LNG='RU';

function get_pagename()
{
	return window.location.pathname.split("/").pop().trim();
}

function ShowError (title, msg)
{
	$.toast({
    heading: title,
    text: (msg),
    showHideTransition: 'plain',
	hideAfter: 5000,
	stack: 4,
	position: 'bottom-right',
    icon: 'error'
	});	
	
}

function ShowSeccess (title, msg)
{
	$.toast({
    heading: title,
    text: (msg),
    showHideTransition: 'plain',
	hideAfter: hide_time*1000,
	stack: 4,
	position: 'bottom-right',
    icon: 'success'
	});	
}
