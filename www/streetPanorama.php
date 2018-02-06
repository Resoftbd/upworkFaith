<!DOCTYPE html>
<html>
  <head>
    <title></title>
    <script type="text/javascript">
      function getParameterByName(name, url) {
          if (!url) url = window.location.href;
          //url = url.toLowerCase(); // This is just to avoid case sensitiveness  
          name = name.replace(/[\[\]]/g, "\\$&");//.toLowerCase();// This is just to avoid case sensitiveness for query parameter name
          var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
              results = regex.exec(url);
          if (!results) return null;
          if (!results[2]) return '';
          return decodeURIComponent(results[2].replace(/\+/g, " "));
      }
      var key = "<?php echo($_GET['key']);?>";//getParameterByName('key');
      var lat = <?php echo($_GET['lat']);?>;//getParameterByName('lat');
      var lng = <?php echo($_GET['lng']);?>;//getParameterByName('lng');

      var script = document.createElement('script');
      script.src = "http://maps.google.com/maps/api/js?key="+key+"&libraries=places";
      document.head.appendChild(script); //or something of the likes

    </script>
     <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
  </head>
  <body onload="drawPanorama()">
    <div id="view"></div>
    <label id="preview"></label>
    <script type="text/javascript">
    k=0;
    function drawPanorama(){
      var pyrmont = new google.maps.LatLng(lat,lng);
      var StreetViewService= new google.maps.StreetViewService();
      StreetViewService.getPanorama({
            location:pyrmont
          },function(streetViewPanoramaData, status){
             if (status === google.maps.StreetViewStatus.OK) {
              $( "#view" ).append( '<div class="panel"><div id="pano_'+k+'" style="height:200px;width:200px;"></div><div id="url_'+k+'"></div><div id="pano_'+k+'_info">Place Id: </div><div id="name_'+k+'">Name: </div><div id="rating_'+k+'">Rating: </div><br />' );
              var panoramaOptions = {
                       position: pyrmont,
                       pov: {
                           heading: 270,
                           pitch: 0
                       },
                       visible: true
                   };
               var data= new google.maps.StreetViewPanorama(document.getElementById('pano_'+k), panoramaOptions);
                data.setPano(streetViewPanoramaData.location.pano);
                  
                var element = document.getElementById('pano_'+k);
                var el = element.getElementsByTagName("a");
                
                setTimeout(function(){
                  var el2 =el[2];
                  //document.writeln(el2);
                  //document.getElementById('preview').innerHTML=el2.toString();
                  //document.getElementById("posturl").submit();
                  //$.post('<?php echo $_SERVER['PHP_SELF']; ?>', {preview: el2.toString();});
                  window.location.href="http://localhost:1314/seeinside/oc/www/streetview.php?preview="+el2.toString();
                },100);
                k++;
             }
          });
    }
    </script>
  </body>
</html>