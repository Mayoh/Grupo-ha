window.kfm_deleteFiles=function(files){
	var names=[],m='';
	var dfiles=[]; // break reference system
	for(var j=0;j<files.length;j++)dfiles.push(files[j]);//breaking reference to selectedFiles
	if(dfiles.length>10){
		for(var i=0;i<9;++i)names.push(File_getInstance(dfiles[i]).name);
		m='<br/>'+kfm.lang.AndNMore(dfiles.length-9);
	}
	else for(var i=0;i<dfiles.length;++i)names.push(File_getInstance(dfiles[i]).name);
	kfm.confirm(kfm.lang.DelMultipleFilesMessage+names.join('<br/>')+m, function(){x_kfm_rm(dfiles,kfm_removeFilesFromView);});
}
