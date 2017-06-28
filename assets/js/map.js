
var map;
function initialize() {
//MAP
var myLatlng = new google.maps.LatLng(14.687639677360217,-61.16513729095459);
var myOptions = {
    zoom: 10,
    center: myLatlng,
	mapTypeControl: false,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  }
map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
//MARKER
var marker = new google.maps.Marker({
    position: myLatlng,
    map: map,
    title:"RESIDENCE CARAÏBE"
});
marker.setAnimation(google.maps.Animation.BOUNCE); 
//INFOBULLE
var infoBubble;
var contentString = '<div id="mapBulle"><b>R&Eacute;SIDENCE CARAÏBE</b><br>1380, Morne aux boeufs<br>97221 Le Carbet MQ</div>';

        infoBubble = new InfoBubble({
          minWidth: 150,
		  maxHeight: 45,
		  content:contentString,
		  position: new google.maps.LatLng(-35, 151),
          shadowStyle: 1,
          padding: 5,
          backgroundColor: '#fadfbe',
          borderRadius: 0,
          arrowSize: 10,
          borderWidth: 1,
          borderColor: '#5e2302',
          disableAutoPan: false,
          hideCloseButton: false,
          arrowPosition: 30,
          arrowStyle: 2
        });

 //CLICK
google.maps.event.addListener(marker, 'click', function() {
if (!infoBubble.isOpen()) {
    infoBubble.open(map, marker);
}
});       


}	 
