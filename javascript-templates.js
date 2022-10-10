jQuery(document).ready(function () {
    
});

/**
 * @param {string} target selector of the target element
 * @param {callback} up execute function when scrolling up
 * @param {callback} down execute function when scrolling down
 * @return {Promise} target element scrollTop Value
 */
 const scroll = async (target, up, down)=>{
  // DETECT SCROLL DIRECTION
  var scrollTop = 0;
  var st = null;
  var html = document.querySelector(target); //just a sample
  document.onscroll = function(e){
      st = window.pageYOffset || document.documentElement.scrollTop;
      if(html){
          if(st > scrollTop){
              down(st);
          }else{
              up(st);
          }
          scrollTop = st <= 0 ? 0 : st;
      }
  }
  return html.scrollTop;
};

// usage
scroll(
  'html',
  (st)=>{console.log(st);},
  (st)=>{console.log(st);}
).then((sc)=>{
  console.log(sc);
});
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

// Timezone converter
function convertTimeZone(date, timeZoneString) {
    return new Promise((resolve, reject)=>{
      resolve(new Date((typeof date === "string" ? new Date(date) : date).toLocaleString("en-US", {timeZone: timeZoneString})));
    }); 
}
// usage
var localtimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
var datetime = "2022-04-23 17:00:00 +10"; //sample date
convertTimeZone(datetime, localtimezone).then(covertedTime=>{
    var d = covertedTime.getDate();
    var Y = covertedTime.getFullYear();
    var m = getMonth(covertedTime.getMonth());
    var fullDate = m + " " + d +", " + Y;
    var time = formatAMPM(covertedTime);
    console.log(fullDate +" "+time);
});
// ######### End timezone converter

// Format AM/PM
function formatAMPM(date) {
   
    var hours = date.getHours();
    var minutes = date.getMinutes();
    var ampm = hours >= 12 ? 'pm' : 'am';
    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    minutes = minutes < 10 ? '0'+minutes : minutes;
    var strTime = hours + ':' + minutes + ' ' + ampm;
    return strTime;
  }

//   with promise
function formatAMPM(date) {
    return new Promise((resolve, reject)=>{
      if(typeof date == "object"){
        var hours = date.getHours();
        var minutes = date.getMinutes();
        var ampm = hours >= 12 ? 'pm' : 'am';
        hours = hours % 12;
        hours = hours ? hours : 12; // the hour '0' should be '12'
        minutes = minutes < 10 ? '0'+minutes : minutes;
        var strTime = hours + ':' + minutes + ' ' + ampm;
        resolve(strTime);
      }else{
        reject("error: parameter type is: "+typeof date+". Must use Date object");
      }
    });
  }
  // usage with promise
  formatAMPM(new Date("2022-04-23 17:00:00 +10"))
    .then(time=>console.log(time));

//   end format AM/PM

// GET QUERY STRING 1
const params = new Proxy(new URLSearchParams(window.location.search), {
  get: (searchParams, prop) => searchParams.get(prop),
});

// GET QUERY STRING 2
const getQueryParams = (params, url) => {
  let href = url
  //this expression is to get the query strings
  let reg = new RegExp("[?&]" + params + "=([^&#]*)", "i")
  let queryString = reg.exec(href)
  return queryString ? queryString[1] : null
}

/**
 * Script I use to download the pdf generated by mpdf using ajax.
 * using xhrFields and script inside success function
 */
jQuery.ajax({
  type: "POST",
  url: "/wp-admin/admin-ajax.php",
  data: {action: "handle_ajax_shortcode", request: "get-pdf"},
  xhrFields: {
      responseType: 'blob'
  },
  success: function (data) {
    //Convert the Byte Data to BLOB object.
      var blob = new Blob([data], { type: "application/octetstream" });
          var fileName = "bmco-product-list.pdf";
      //Check the Browser type and download the File.
      var isIE = false || !!document.documentMode;
      if (isIE) {
          window.navigator.msSaveBlob(blob, fileName);
      } else {
          var url = window.URL || window.webkitURL;
          link = url.createObjectURL(blob);
          var a = jQuery("<a />");
          a.attr("download", fileName);
          a.attr("href", link);
          jQuery("body").append(a);
          a[0].click();
          jQuery("body").remove(a);
      }
      jQuery('.search-loader').remove();
  }
});

/**End ######################## */

/**
 * Woocommerce Add/Remove cart events
 * "Enable AJAX add to cart buttons on archives" must be enabled in WooCommerce->Settings->Products
 * Reference https://wordpress.stackexchange.com/questions/342148/list-of-js-events-in-the-woocommerce-frontend
 */
jQuery(document).ready(function ($) {
  $(document.body).on("added_to_cart", (e, fragments, cart_hash, this_button)=>{
    var cart_count_el = $('.cart-icon').attr('cart-count');
    var current_count = parseInt(cart_count_el);
    cart_count_el = current_count + 1;
    $('.cart-icon').attr('cart-count', cart_count_el);
    console.log(cart_count_el);
  });

  $(document.body).on("removed_from_cart", (e, fragments, cart_hash, this_button)=>{
    var cart_count_el = $('.cart-icon').attr('cart-count');
    var current_count = parseInt(cart_count_el);
    cart_count_el = (current_count) != 0 ? current_count - 1 : current_count;
    console.log(cart_count_el);
    $('.cart-icon').attr('cart-count', cart_count_el);
  });

  $(document.body).on("wc_cart_emptied", (e, fragments, cart_hash, this_button)=>{
    console.log(cart_count_el);
    $('.cart-icon').attr('cart-count', 0);
  });
});

// ################# END "Woocommerce Add/Remove cart events"

/**
 * Custom JS event listener function
 * @param {string} type The type of DOM event
 * @param {string} selector DOM selector
 * @param {function} callback Fires when the event is active 
 */

function addGlobalEventListener(type, selector, callback){
  document.addEventListener(type, e =>{
    if(e.target.matches(selector)) callback(e);
  });
}

// END "Custom JS event listener function" #######################################

/**
 * Promise simple/basic syntax
 */

let myPromise = (param)=>{
  return new Promise((resolve, reject)=>{
    if(true){
      resolve({data: "resolved"});
    }else{
      reject({data: "rejected"});
    }
  });
}

// usage 
myPromise('param')
  .then((data)=>{
    // your code here
    console.log(data);
    return {anotherData: ""};
  })
  .then((anotherData)=>{
    console.log(anotherData);
});

// can also use async function - returns a promise
// usage is the same as above

let myPromiseAsync = async (param)=>{
  return {data: "data returned"};
}; 

// END Promise syntax ############################################

/**
 * Sweet Alert in CF7
 * Add Sweet alert when contact form 7 is submitted
 * {cdn} <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 * selft invoked function
 */

 const form_submitted = (()=>{
  var wpcf7Elm = document.querySelector( '.wpcf7' );
  var detail = null;
  wpcf7Elm.addEventListener( 'wpcf7submit', ( event ) => {
    detail = event.detail;
    var status = event.detail.apiResponse.status;
    var icon = (status == "validation_failed") ? "error" : "success";
    swal.fire({
    icon: icon,
    text: event.detail.apiResponse.message,
  });
  }, false );

  return ()=>{
    return detail
  };
})();