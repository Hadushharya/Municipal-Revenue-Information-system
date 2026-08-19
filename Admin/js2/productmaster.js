 // ajax function to check Product ID already exist .
 // ajax function Sub category .

 function GetXmlHttpObject()
 {
  if (window.XMLHttpRequest)
   {
     return new XMLHttpRequest();
   }
   else if (window.ActiveXObject)
   {
   // code for IE6, IE5
     return new ActiveXObject("Microsoft.XMLHTTP");
   }
   return null;
 }

 

 // sub categoty function 
 var XMLHttpPrdsubcat=false;
 function displaysubcat(cat)
 {
   XMLHttpPrdsubcat=GetXmlHttpObject();   
  if (XMLHttpPrdsubcat==null){
      alert ("Your browser does not support AJAX!");
      return;
    }
    XMLHttpPrdsubcat.open("GET","subcat?catval1="+cat,true);
    XMLHttpPrdsubcat.onreadystatechange = function(){
       if (XMLHttpPrdsubcat.readyState==4 && XMLHttpPrdsubcat.status == 200){
           document.getElementById('subcat1div').innerHTML=XMLHttpPrdsubcat.responseText; 
        }  
    }
    XMLHttpPrdsubcat.send(null); 
 }
 