jQuery(document).ready(function () {
    
});

// DETECT SCROLL DIRECTION
var scrollTop = 0;
document.onscroll = function(e){
    var html = document.querySelector('html'); //just a sample
	var header = document.querySelector('#main-header-section'); //just a sample
    var st = window.pageYOffset || document.documentElement.scrollTop;
    if(html){
		if(header){
            if(st > scrollTop){
                // downscroll code
            }else{
                // upscroll code
            }
		}
    scrollTop = st <= 0 ? 0 : st;
    }
}
// #####DETECT SCROLL DIRECTION END ##########################