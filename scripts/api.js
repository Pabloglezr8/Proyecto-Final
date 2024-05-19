function iniciarMap(){
  var coord = {lat:43.5306393,lng: -5.6471569};
  var map = new google.maps.Map(document.getElementById('map'),{
    zoom: 18,
    center: coord
  });
  var marker = new google.maps.Marker({
    position: coord,
    map: map
  });

}