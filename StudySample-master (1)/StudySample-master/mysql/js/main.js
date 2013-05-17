//全选
$('input[name=select]',form).click(function(){
	$('input[name^=list]:checkbox,input[name=select]:checkbox',form).attr('checked',this.checked);
});
