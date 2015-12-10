function money_select()
{
	var oUl= document.getElementById('radiolist');
	var oLi = document.getElementsByTagName('li');
	var amount = document.getElementById('amount');
	var i = 0;   
	for(i=0;i<oLi.length;i++)
	{
		oLi[i].onclick=function(){
			for(i=0;i<oLi.length;i++)
			{
				oLi[i].className='';
			}
			this.className='select'
			amount.value = this.attributes["data-id"].nodeValue;
		} 
	}
}
money_select();//充值选择JS