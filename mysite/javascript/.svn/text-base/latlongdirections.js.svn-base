//Method to extract parameters from query string taken from here: http://feather.elektrum.org/book/src.html

//Make script aware of itself
var scripts = document.getElementsByTagName('script');
var myScript = scripts[scripts.length - 1];

//Extract latitude and longitude from Javascript query string
var queryString = myScript.src.replace(/^[^\?]+\??/, '');

var params = parseQuery(queryString);

function parseQuery(query) {
    var Params = new Object();
    if (!query) return Params; // return empty object
    var Pairs = query.split(/[;&]/);
    for (var i = 0; i < Pairs.length; i++) {
        var KeyVal = Pairs[i].split('=');
        if (!KeyVal || KeyVal.length != 2) continue;
        var key = unescape(KeyVal[0]);
        var val = unescape(KeyVal[1]);
        val = val.replace(/\+/g, ' ');
        Params[key] = val;
    }
    return Params;
}

//Output Google Maps centred on lat/long
google.load("maps", "2");

    
      var gmarkers = [];
      var map;
      var gdir;
      var htmls = [];
      // arrays to hold variants of the info window html with get direction forms open
      var to_htmls = [];
      var from_htmls = [];



      // A function to create the marker and set up the event window
      function createMarker(point,name,html) {
        var marker = new GMarker(point);

        var i = gmarkers.length;

        // The info window version with the "to here" form open
        to_htmls[i] = html + '<br>Directions: <b>To here<\/b> - <a href="javascript:fromhere(' + i + ')">From here<\/a>' +
           '<br>Start address:<form action="javascript:getDirections()">' +
           '<input type="text" SIZE=40 MAXLENGTH=40 name="saddr" id="saddr" value="" /><br>' +
           '<INPUT value="Get Directions" TYPE="SUBMIT"><br>' +
           'Walk <input type="checkbox" name="walk" id="walk" /> &nbsp; Avoid Highways <input type="checkbox" name="highways" id="highways" />' +
           '<input type="hidden" id="daddr" value="'+name+"@"+ point.lat() + ',' + point.lng() + 
           '"/>';
        // The info window version with the "from here" form open
        from_htmls[i] = html + '<br>Directions: <a href="javascript:tohere(' + i + ')">To here<\/a> - <b>From here<\/b>' +
           '<br>End address:<form action="javascript:getDirections()">' +
           '<input type="text" SIZE=40 MAXLENGTH=40 name="daddr" id="daddr" value="" /><br>' +
           '<INPUT value="Get Directions" TYPE="SUBMIT"><br>' +
           'Walk <input type="checkbox" name="walk" id="walk" /> &nbsp; Avoid Highways <input type="checkbox" name="highways" id="highways" />' +
           '<input type="hidden" id="saddr" value="'+name+"@"+ point.lat() + ',' + point.lng() +
           '"/>';
        // The inactive version of the direction info
        html = html + '<br>Directions: <a href="javascript:tohere('+i+')">To here<\/a> - <a href="javascript:fromhere('+i+')">From here<\/a>';

        marker.openInfoWindowHtml(html);
        
        // save the info we need to use later for the side_bar
        gmarkers.push(marker);
        htmls[i] = html;
        return marker;
      }

      // ===== request the directions =====
      function getDirections() {
        // ==== Set up the walk and avoid highways options ====
        var opts = {};
        if (document.getElementById("walk").checked) {
           opts.travelMode = G_TRAVEL_MODE_WALKING;
        }
        if (document.getElementById("highways").checked) {
           opts.avoidHighways = true;
        }
        // ==== set the start and end locations ====
        var saddr = document.getElementById("saddr").value
        var daddr = document.getElementById("daddr").value
        
        // === create a GDirections Object ===
        var gdir=new GDirections(map, document.getElementById("directions"));
        
        
       // === Array for decoding the failure codes ===
      var reasons=[];
      reasons[G_GEO_SUCCESS]            = "Success";
      reasons[G_GEO_MISSING_ADDRESS]    = "Missing Address: The address was either missing or had no value.";
      reasons[G_GEO_UNKNOWN_ADDRESS]    = "Unknown Address:  No corresponding geographic location could be found for the specified address.";
      reasons[G_GEO_UNAVAILABLE_ADDRESS]= "Unavailable Address:  The geocode for the given address cannot be returned due to legal or contractual reasons.";
      reasons[G_GEO_BAD_KEY]            = "Bad Key: The API key is either invalid or does not match the domain for which it was given";
      reasons[G_GEO_TOO_MANY_QUERIES]   = "Too Many Queries: The daily geocoding quota for this site has been exceeded.";
      reasons[G_GEO_SERVER_ERROR]       = "Server error: The geocoding request could not be successfully processed.";
      reasons[G_GEO_BAD_REQUEST]        = "A directions request could not be successfully parsed.";
      reasons[G_GEO_MISSING_QUERY]      = "No query was specified in the input.";
      reasons[G_GEO_UNKNOWN_DIRECTIONS] = "The GDirections object could not compute directions between the points.";

      // === catch Directions errors ===
      GEvent.addListener(gdir, "error", function() {
        var code = gdir.getStatus().code;
        var reason="Code "+code;
        if (reasons[code]) {
          reason = reasons[code]
        } 

        alert("Failed to obtain directions, "+reason);
      });
      
 
 // Clear existing directions, add link to full directions
        document.getElementById("directions").innerHTML='<a href="http://maps.google.co.nz/maps?f=d&source=s_d&saddr='+saddr+'&daddr='+daddr+'&hl=en" target="_blank">New Window with Full Map and Directions</a>';
 
        gdir.load("from: "+saddr+" to: "+daddr, opts);
      }


      // This function picks up the click and opens the corresponding info window
      function myclick(i) {
        gmarkers[i].openInfoWindowHtml(htmls[i]);
      }

      // functions that open the directions forms
      function tohere(i) {
        gmarkers[i].openInfoWindowHtml(to_htmls[i]);
      }
      function fromhere(i) {
        gmarkers[i].openInfoWindowHtml(from_htmls[i]);
      }

function initialize(){
    // create the map
    params['mapzoom'] == 0 ? params['mapzoom'] = 14 : params['mapzoom'] = parseInt(params['mapzoom']);
    
    var map = new google.maps.Map2(document.getElementById('map_canvas'));
    map.setCenter(new google.maps.LatLng(params['lat'], params['long']), params['mapzoom']);
    map.setUIToDefault();
    map.disableScrollWheelZoom(); 

    // Create marker point
        var point = new GLatLng(params['lat'],params['long']);
        var html = "";
        var label = "";
        // create the marker
        var marker = createMarker(point,label,html);
        map.addOverlay(marker);
   }

google.setOnLoadCallback(initialize);