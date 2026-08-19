<!DOCTYPE html>
<html lang="en-US">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width">


<script type="text/javascript">
	//offset is # days between 1/1/1970 (UNIX time) and the calendar beginning year
	//Exampe with 1753 beginning year
	//1970-1753=217 years 
	// 217 * 365 = 79205 days
	// add leap year days between 1753 and 1970 = 52 , 79205 + 52 = 79257
	// the remaining 115 days account for the number of days since Meskerem 1 
	// to January 1 of 1953 (disregarding the year) to align jan 1 to an 
	// Ethiopian date (Tahsas xx).
	// It happens that in 1953, Jan 1 = Tahsas 25. That is Meskerem, Tikimt, Hidar 
	// with 30 days each equal 90 plus 25 (Tahsas) equals 115
	// the final offset equals 79372 = 79257 (from prev calc) + 115 
	OFFSET=79372,DAY=1000*60*60*24
	var EYear,EMonth,EDate,GC
	var months="መስከረም,ጥቅምት,ኅዳር,ታኅሣሥ,ጥር,የካቲት,መጋቢት,ሚያዝያ,ግንቦት,ሰኔ,ሐምሌ,ነሐሴ,ጳጉሜ".split(",")
	  
	function isValid(dt){
		GC=new Date(dt)
		yearlen=(dt.substr(dt.lastIndexOf("/")+1)).length
		if (yearlen!=4)
			return false
		else return (GC.getFullYear()>=1753)
		}
		
	function getECDays(dt){
		UTCVal=Date.UTC(GC.getFullYear(),GC.getMonth(),GC.getDate())
		return OFFSET+(UTCVal/DAY)
		}
		
	function ECDate(dt){
		days=getECDays(dt)
		EYear=1745
		//approximation with errors on EYear %4 = 0
		years_applied=Math.floor(days/365.25)
		EYear=1745+years_applied
		days_remaining=days-Math.floor(years_applied*365.25)
		
		//fix the approximation error
		if (EYear%4==0)	  
		  days_remaining--

		if (days_remaining==0){
			EYear--
			EMonth=13
			EDate=5 + ((EYear % 4 ==3)?1:0)
			}
		else{
			EMonth = Math.ceil(days_remaining / 30)
			if (days_remaining % 30 ==0)
				EDate = 30
			else EDate=days_remaining % 30
			}
		return EMonth + "/"+ EDate + "/" + EYear
		}
		
	function getInWords(){
		return months[EMonth-1]+" "+EDate+", "+EYear
		}
	function getECDate(){
		var date2 = new Date(date1.getTime());.
		if (isValid(document.theForm.input.value)){
			document.theForm.output.value=ECDate(document.theForm.input.value)
			document.theForm.output2.value=getInWords()
			}
		else{
			alert ("Gregorian date cannot be earlier than 1/1/1753!")
			}
	}

	function getKeyCode(e){
		document.theForm.output.value="";
		document.theForm.output2.value=""
		if (!e) e=window.event
		chr = e.which||e.keyCode
		if (chr==13)
			getECDate();
		else if (chr!=8 && (chr<47 || chr>57)){
			return false
		}
	}

	function getToday(){
		d=document;
		re=d.all?d.all['today']:d.getElementById('today');
		GC=new Date()
		re.innerHTML="" + ECDate()+" "+getInWords()+"E.C<br/>"
		}
</script> 
<div >
<h1>&nbsp;</h1>
<H2 align='center'><font color='none'>Gregorian to Ethiopian Calendar Converter</font></h2>
<h4 align='center'><font color='none'><br></font></h4>
<table align='center' cols='2' cellpadding='1' cellspacing='1' border='1'>
<form name="theForm">

<tr><td><font color='none'>
Enter a Gregorian Calendar date as (mm/dd/yyyy)<td width='10'>
<input type='text' name="input" size='45' maxlength='10'
onChange="getECDate()" onKeyPress="return getKeyCode(event)"></tr>
<tr><td><font color='none'>
Corresponding Ethiopian calendar date
<td  width='10'><input type='text' name="output" size='45'</td></tr>
<tr><tr><td><font color='none'>
Ethiopian calendar date in Amharic
<td  width='10'><input type='text' name="output2" size='20'</td></tr>
</form>
<tr><td colspan='2'><div id='today'></div></td></tr></table>
</div >