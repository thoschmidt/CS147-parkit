
	var flagclock = 0;
	var flagstop = 0;
	var stoptime = 0;
	var splitcounter = 0;
	var currenttime;
	var splitdate = '';
	var output;
	var clock;

	function startstop()
		{
		var startdate = new Date();
		var starttime = startdate.getTime();
		if(flagclock==0)
			{
			flagclock = 1;
			counter(starttime);
			}
		else
			{
			flagclock = 0;
			flagstop = 1;
			splitdate = '';
			}
		}

	function counter(starttime)
		{
		output = document.getElementById('output');
		clock = document.getElementById('clock');
		currenttime = new Date();
		var timediff = currenttime.getTime() - starttime;
		if(flagstop == 1)
			{
			timediff = timediff + stoptime
			}
		if(flagclock == 1)
			{
			clock.value = formattime(timediff,'');
			refresh = setTimeout('counter(' + starttime + ');',10);
			}
		else
			{
			window.clearTimeout(refresh);
			stoptime = timediff;
			}
		}

	function formattime(rawtime,roundtype)
		{
		if(roundtype == 'round')
			{
			var ds = Math.round(rawtime/100) + '';
			}
		else
			{
			var ds = Math.floor(rawtime/100) + '';
			}
		var sec = Math.floor(rawtime/1000);
		var min = Math.floor(rawtime/60000);
		ds = ds.charAt(ds.length - 1);
		if(min >= 60)
			{
			startstop();
			}
		sec = sec - 60 * min + '';
		if(sec.charAt(sec.length - 2) != '')
			{
			sec = sec.charAt(sec.length - 2) + sec.charAt(sec.length - 1);
			}
		else
			{
			sec = 0 + sec.charAt(sec.length - 1);
			}
		min = min + '';
		if(min.charAt(min.length - 2) != '')
			{
			min = min.charAt(min.length - 2)+min.charAt(min.length - 1);
			}
		else
			{
			min = 0 + min.charAt(min.length - 1);
			}
		return min + ':' + sec + ':' + ds;
		}
