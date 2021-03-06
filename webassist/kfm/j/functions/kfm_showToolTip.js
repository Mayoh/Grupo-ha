window.kfm_showToolTip=function(id){
	var ws,win,F,icon,table,l,t,w,h;
	if(!id || kfm_listview)return;
	F=File_getInstance(id);
	table=kfm_buildFileDetailsTable(F);
	icon=document.getElementById('kfm_file_icon_'+id);
	if(!icon||contextmenu)return;
	table.id='kfm_tooltip';
	kfm.addEl(document.body,table);
	l=getOffset(icon,'Left');
	t=getOffset(icon,'Top');
	w=icon.offsetWidth;
	h=icon.offsetHeight;
	win=$j(window);
	ws={x:win.width(),y:win.height()};
	l=(l+(w/2)>ws.x/2)?l-table.offsetWidth:l+w;
	table.style.position  ='absolute';
	table.style.top       =t+'px';
	table.style.left      =l+'px';
	table.style.visibility='visible';
	table.style.opacity   =.9;
}
