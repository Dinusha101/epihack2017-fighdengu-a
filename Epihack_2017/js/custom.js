// JavaScript Document

	function selectRow(row){
		document.getElementById(row).style.background="#fbf7e9";
	}

	function deselectRow(row,str){
		if(str=="No")
		{
			document.getElementById(row).style.background="";
		}
		else
		{
			document.getElementById(row).style.background="#f9f9f9";
		}
	}

var cond = 0;
function lnkChkAll(cond,frmname)
{
	frmobj = document.forms[frmname];
	elmlen=frmobj.length
	k=1;
	for(i=0,j=1;i<elmlen;i++,j++)
	{
		var ele=frmobj.elements[i].name;
		var subele=ele.substring(0,3);
		if(subele!="top"&&subele!="new")
		{
			if(frmobj.elements[i].type=="checkbox"&&cond==0)
			{
				frmobj.elements[i].checked=true;
				document.getElementById('row'+k).style.background="#fbf7e9";
				k++;
			}
			else
			{
				if(frmobj.elements[i].type=="checkbox")
				{
					frmobj.elements[i].checked=false;
					str=document.getElementById('hid_act'+k).value;
					if(str=="No")
					{
						document.getElementById('row'+k).style.background="";
					}
					else
					{
						document.getElementById('row'+k).style.background="#f9f9f9";
					}
					k++;
				}
			}
		}
	}
}

	function iselectRow(irow){
		document.getElementById(irow).style.background="#fbf7e9";
	}

	function ideselectRow(irow,str){
		if(str=="No")
		{
			document.getElementById(irow).style.background="";
		}
		else
		{
			document.getElementById(irow).style.background="#f9f9f9";
		}
	}

var cond1 = 0;
function ilnkChkAll(cond1,frmname)
{
	frmobj = document.forms[frmname];
	elmlen=frmobj.length
	k=1;
	for(i=0,j=1;i<elmlen;i++,j++)
	{
		var ele=frmobj.elements[i].name;
		var subele=ele.substring(0,3);
		if(subele!="top"&&subele!="new")
		{
			if(frmobj.elements[i].type=="checkbox"&&cond1==0)
			{
				frmobj.elements[i].checked=true;
				document.getElementById('irow'+k).style.background="#fbf7e9";
				k++;
			}
			else
			{
				if(frmobj.elements[i].type=="checkbox")
				{
					frmobj.elements[i].checked=false;
					str=document.getElementById('ihid_act'+k).value;
					if(str=="No")
					{
						document.getElementById('irow'+k).style.background="";
					}
					else
					{
						document.getElementById('irow'+k).style.background="#f9f9f9";
					}
					k++;
				}
			}
		}
	}
}

