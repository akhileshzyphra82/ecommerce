/*******************************************************************************

*

* The "options" parameter is an anonymous object which includes the following

* available options:

*

* params:    Parameters for the requested url in the format p1=1&p2=0&p3=2

* meth:      The request method. Can be "get" or "post". Default is "post".

* async:     Toggles asynchronous mode. Default is true.

* startfunc: A function or list of functions to be called before the AJAX

*            request is made. A list of functions must be separated by the

*            semi-colon like this: "showLoad(); animateText(); hideDiv('divname')".

*            You can pass parameters into the functions.

* endfunc:   A function or list of functions to be called after a successful

*            AJAX request. Uses the same format as "startfunc".

* errorfunc: A function or list of functions to be called when the AJAX request

*            is unsuccessful. Uses the same format as "startfunc".

*

* Returns true on success and false on failure.

*

* Example Usage:

*

  callAjax( "rightdiv", "getData.php", {

    params:"id=12&name=sameer",

    meth:"post",

    async:true,

    startfunc:"elemOn('loading')",

    endfunc:"elemOff('loading'); elemOn('rightdiv')",

    errorfunc:"ajaxError()" }

  );

*/


function callAjax( elemid, url, options )
{
	var params = options.params || "";
	var meth = options.meth || "post";
	var async = options.mode || true;
	var startfunc = options.startfunc || "";
	var endfunc = options.endfunc || "";
	var errorfunc = options.errorfunc || "";
	if( startfunc != "" )
		eval( startfunc );
	var url_with_param = url+( params != "" ? "?"+params : "" );
	loadXMLDoc_rand();
	var xmlhttp_rand
	function loadXMLDoc_rand()
	{
	    if (window.ActiveXObject)
	    {
	        try{//for old versions of IE
	            xmlhttp_rand = new ActiveXObject("Msxml2.XMLHTTP");
	        }
	        catch(e){
            	try{
	            xmlhttp_rand=new ActiveXObject("Microsoft.XMLHTTP");
	        	}
	        	catch(e){}
	        }
	        if (xmlhttp_rand)
	        {
              	xmlhttp_rand.onreadystatechange=xmlhttpChange_rand
	        }
	        else
	        {
           		alert( "Your browser cannot perform the requested action. "+
	             "Either your security settings are too high or your "+
	             "browser is outdated. Try the newest version of "+
	             "Internet Explorer or Mozilla Firefox." );
	            return false;
	        }
	    }
	    else if (window.XMLHttpRequest)
	    {
        	xmlhttp_rand=new XMLHttpRequest()
	        xmlhttp_rand.onreadystatechange=xmlhttpChange_rand
        	}
        if(xmlhttp_rand)
        {
            if(meth=="get")
           {
                xmlhttp_rand.open('GET',url_with_param,async);
                xmlhttp_rand.send(true);
            }
            else if(meth=="post")
            {
                xmlhttp_rand.open('POST',url,async);
                xmlhttp_rand.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=UTF-8");
                xmlhttp_rand.setRequestHeader("Content-length", params.length);
                xmlhttp_rand.setRequestHeader("Connection", "close");
                xmlhttp_rand.send(params);
            }
        }
        return false;
	}
	function xmlhttpChange_rand()
	{
   	 if (xmlhttp_rand.readyState==4)
	    {
	        if (xmlhttp_rand.status==200)
	        {// OK status
	             var objXML = xmlhttp_rand.responseXML;
	             var objXML1 = xmlhttp_rand.responseText;
            	if(elemid != "")
	            {
					if(elemid.indexOf(',')== -1)
					{
	                	document.getElementById(elemid).innerHTML = objXML1;
					}
					else
					 {
					 	var myArray = new Array();
					 	var tmp= "";
						var cc=0;
						for(var cnt=0; cnt<elemid.length; cnt++)
						{
							if(elemid[cnt]==',')
							{
								myArray[cc]=tmp; 
								cc++;
								tmp="";
							}
							else
							{
								tmp=tmp.concat(elemid[cnt]);
							}
						}
						myArray[cc]=tmp; 
						for(var ecnt=0; ecnt<myArray.length; ecnt++)
						{	
							document.getElementById(myArray[ecnt]).innerHTML = objXML1;
						}
					 }		
	            }
	            if( endfunc != "" )
	                eval( endfunc );
	        }
	        else
	        {
	            alert("Problem retrieving XML data:\n"+xmlhttp_rand.statusText);
	            if( endfunc != "" )
	                eval( endfunc );
	            if( errorfunc != "" )
	                eval( errorfunc );
	            return false;
	        }
	    }
	}
}
function s_function(div_id)
{
	document.getElementById(div_id).innerHTML='<img src="../Images/ajax-loader-2.gif" width="60" height="12" />';
}
function e_function(div_id)
{
	document.getElementById(div_id).disabled=false;
}
