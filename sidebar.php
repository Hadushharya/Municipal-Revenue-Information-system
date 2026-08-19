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
		re.innerHTML=" "+ECDate()+" E.C<br/>"
		}
</script> 

			<div class="main-container ace-save-state" id="main-container">
			<script type="text/javascript">
				try{ace.settings.loadState('main-container')}catch(e){}
			</script>

			<div id="sidebar" class="sidebar                  responsive                    ace-save-state">
				<script type="text/javascript">
					try{ace.settings.loadState('sidebar')}catch(e){}
				</script>

				 

				<ul class="nav nav-list">
			 	 
			    
					<li class="active">
						<a href="#?categ=all active">
							<i class=" "> <div id='today' getToday();  class="red"   ></div></i>
							<span class="menu-text">  </span>
						</a>

						<b class="arrow"></b>
					</li>
					 <li class="active">
						<a href="dashboard2?categ=all">
							<i class="menu-icon fa fa-list blue "></i>
							<span class="menu-text">ወርሓዊ ሓበሬታ</span>
						</a>

						<b class="arrow"></b>
					</li>
					 <li class="active">
						<a href="dashboard?categ=all">
							<i class="menu-icon fa fa-list blue "></i>
							<span class="menu-text">ዓመታዊ ሓበሬታ</span>
						</a>

						<b class="arrow"></b>
					</li>
				 <li class="active">
						<a href="vissionmission?categ=all">
							<i class="menu-icon fa fa-list blue "></i>
							<span class="menu-text">ራእይ/ልኡኽ/ክብርታት</span>
						</a>

						<b class="arrow"></b>
					</li>
				 	 
						 
				
						 

                  </ul>
					</li>
				</ul><!-- /.nav-list -->

				 
			</div>

			<div class="main-content">
				<div class="main-content-inner">
					<div class="breadcrumbs ace-save-state" id="breadcrumbs">
						<ul class="breadcrumb">
							 
							<li class="active"> 
							 
							</li>
						</ul><!-- /.breadcrumb -->

						 
					</div>

					