jQuery(document).ready(function () {
    
});

// DETECT SCROLL DIRECTION
var scrollTop = 0;
document.onscroll = function(e){
    var html = document.querySelector('html'); //just a sample
	var header = document.querySelector('header selector'); //just a sample
    var st = window.pageYOffset || document.documentElement.scrollTop;
    if(html){
		if(header){
            if(st > scrollTop){
                // downscroll code
                console.log('down '+st);
            }else{
                // upscroll code
                console.log('up '+ st);
            }
		}
    scrollTop = st <= 0 ? 0 : st;
    }
}
// #####DETECT SCROLL DIRECTION END ##########################

 

// CLOCK WITH DATE

function getMonth(index){
	var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
	return months[index];
}
function getDay(index){
	var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Firday',' Satruday'];
	return days[index];
}

jQuery(document).ready(function ($) {
function startTime() {
    var today = new Date();
    var month =  getMonth(today.getMonth());
    var day = getDay(today.getDay());
    var date = today.getDate();
    var date_for_switch = checkTime(date);
    switch (date_for_switch[1]) {
        case "1": date = date+"st"; break;
        case "2": date = date+ "nd"; break;
        case "3": date = date+ "rd"; break;
        default: date = date+ "th"; break;
    }
    var h = today.getHours();
    var ampm = h >= 12 ? 'PM' : 'AM';
    h = h % 12;
    h = h ? h : 12; // the hour '0' should be '12'
    var m = today.getMinutes();
    var s = today.getSeconds();
    var y = today.getFullYear();
    m = checkTime(m);
    s = checkTime(s);
    var thetime =  day + " " + date + " of " + month + " "+ y + " " + h + ":" + m  +":"+s+" "+ampm+ " PHT";
    jQuery('div#date-and-time > span').html(thetime);
    var t = setTimeout(startTime, 500);
}
function checkTime(i) {
    if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
    return i;
}
	
startTime(); //CALLING
});
// #############END########################