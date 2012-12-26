//////  CREACION  OBJETO  AJAX //////////////////
// holds an instance of XMLHttpRequest
var xmlHttp = createXmlHttpRequestObject();

// creates an XMLHttpRequest instance
function createXmlHttpRequestObject() 
{
  // will store the reference to the XMLHttpRequest object
  var xmlHttp;
  // this should work for all browsers except IE6 and older
  try
  {
    // try to create XMLHttpRequest object
    xmlHttp = new XMLHttpRequest();
  }
  catch(e)
  {
    // assume IE6 or older
    var XmlHttpVersions = new Array('MSXML2.XMLHTTP.6.0',
                                    'MSXML2.XMLHTTP.5.0',
                                    'MSXML2.XMLHTTP.4.0',
                                    'MSXML2.XMLHTTP.3.0',
                                    'MSXML2.XMLHTTP',
                                    'Microsoft.XMLHTTP');
    // try every prog id until one works
    for (var i=0; i<XmlHttpVersions.length && !xmlHttp; i++) 
    {
      try 
 
      { 
        // try to create XMLHttpRequest object
        xmlHttp = new ActiveXObject(XmlHttpVersions[i]);
      } 
      catch (e) {}
    }
  }
  // return the created object or display an error message
  if (!xmlHttp)
    alert("Error creating the XMLHttpRequest object.");
  else 
    return xmlHttp;
}


// function called when the state of the HTTP request changes
function handleRequestStateChange() 
{
  // when readyState is 4, we are ready to read the server response
  if (xmlHttp.readyState == 4) 
  {
    // continue only if HTTP status is "OK"
    if (xmlHttp.status == 200) 
    {
      try
      {
        // do something with the response from the server
        handleServerResponse();
      }
      catch(e)
      {
        // display error message
        alert("Error reading the response: " + e.toString());
      }
    } 
    else
    {
      // display status message
      alert("There was a problem retrieving the data:\n" + 
            xmlHttp.statusText);
    }
  }
}