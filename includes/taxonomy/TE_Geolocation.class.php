<?php

class TE_Geolocation extends TaxonomyElements
{
    protected static $NAME = 'Geolocation';
    
    function __construct($args=array())
    {
        parent::__construct($args);
    }
    
    public function FormConfig()
    {
        parent::FormConfig();
    }
    
    public function SaveFormConfig($args = array())
    {
        parent::SaveFormConfig($args);
    }
    
    public function FormEdit($page) {
        $geolocation = $page->GetMeta($this->GetAttrName());
        $latitude = isset($geolocation['latitude'])?$geolocation['latitude']:'';
        $longitude = isset($geolocation['longitude'])?$geolocation['longitude']:'';
        $center_lat = empty($latitude) ? '40.415881657041254' : $latitude;
        $center_lng = empty($latitude) ? '-3.6938544946324328' : $longitude;
        ?>
        <p>
            <label for="latitude-<?php print $this->GetAttrName(); ?>"><?php print_text( 'Latitude' ); ?></label>
            <input type="text" class="input-block-level" name="latitude-<?php print $this->GetAttrName(); ?>" id="latitude-<?php print $this->GetAttrName(); ?>" value="<?php print_html_attr($latitude); ?>" /><br />
        </p>
        <p>
            <label for="longitude-<?php print $this->GetAttrName(); ?>"><?php print_text( 'Longitude' ); ?></label>
            <input type="text" class="input-block-level" name="longitude-<?php print $this->GetAttrName(); ?>" id="longitude-<?php print $this->GetAttrName(); ?>" value="<?php print_html_attr($longitude); ?>" />
        </p>
        <script>
            var markers = [];
            var map;
            function initialize() {
                var mapOptions = {
                    zoom: 5,
                    center: new google.maps.LatLng(<?php print($center_lat); ?>,<?php print($center_lng); ?>),
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };
                map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);

                google.maps.event.addListener(map, 'click', function(e) {
                    placeMarker(e.latLng, map);
                });
                
                <?php if (!empty($latitude) && !empty($longitude)): ?>
                placeMarker(new google.maps.LatLng(<?php print($latitude); ?>,<?php print($longitude); ?>), map);
                <?php endif; ?>
            }
            function placeMarker(position, map) {
                deleteOverlays();
                document.getElementById('latitude-<?php print $this->GetAttrName(); ?>').value = position.lat();
                document.getElementById('longitude-<?php print $this->GetAttrName(); ?>').value = position.lng();
                var marker = new google.maps.Marker({
                    position: position,
                    map: map
                });
                map.panTo(position);
                markers.push(marker);
            }
            function deleteOverlays() {
                for (var i = 0; i < markers.length; i++) {
                    markers[i].setMap(null);
                }
                markers = [];
            }
            google.maps.event.addDomListener(window, 'load', initialize);
        </script>
        <div id="map_canvas" class="gmap"></div>
        <?php
        parent::FormEdit($page);
    }
    
    public function GetFormEdit($args=array())
    {
        $geolocation = isset($args[$this->GetAttrName()]) ? $args[$this->GetAttrName()] : array(
            'latitude' => get_request_p('latitude-'.$this->GetAttrName(), '', true),
            'longitude' => get_request_p('longitude-'.$this->GetAttrName(), '', true)
        );
        return $geolocation;
    }
    
    public function SaveFormEdit($page, $args=array())
    {
        $page->SaveMeta($this->GetAttrName(), $this->GetFormEdit($args));
    }
    
    
    
}
