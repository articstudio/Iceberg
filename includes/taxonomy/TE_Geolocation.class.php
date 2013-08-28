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
        $uniqe = uniqid();
        $geolocation = $page->GetMeta($this->GetAttrName());
        $latitude = isset($geolocation['latitude'])?$geolocation['latitude']:'';
        $longitude = isset($geolocation['longitude'])?$geolocation['longitude']:'';
        $center_lat = empty($latitude) ? '40.415881657041254' : $latitude;
        $center_lng = empty($latitude) ? '-3.6938544946324328' : $longitude;
        ?>
        <p>
            <label for="latitude-<?php print $this->GetAttrName(); ?>"><?php print_text( 'Latitude' ); ?></label>
            <input type="text" class="input-block-level" name="latitude-<?php print $this->GetAttrName(); ?>" id="latitude-<?php print $this->GetAttrName(); ?>-<?php print $uniqe; ?>" value="<?php print_html_attr($latitude); ?>" /><br />
        </p>
        <p>
            <label for="longitude-<?php print $this->GetAttrName(); ?>-<?php print $uniqe; ?>"><?php print_text( 'Longitude' ); ?></label>
            <input type="text" class="input-block-level" name="longitude-<?php print $this->GetAttrName(); ?>" id="longitude-<?php print $this->GetAttrName(); ?>-<?php print $uniqe; ?>" value="<?php print_html_attr($longitude); ?>" />
        </p>
        <div id="map_canvas-<?php print $this->GetAttrName(); ?>" data-center="<?php print($center_lat); ?>,<?php print($center_lng); ?>" data-marker="<?php print($latitude); ?>,<?php print($longitude); ?>" data-action="place-marker" class="gmap" data-latitude="latitude-<?php print $this->GetAttrName(); ?>-<?php print $uniqe; ?>" data-longitude="longitude-<?php print $this->GetAttrName(); ?>-<?php print $uniqe; ?>"></div>
        <?php
        parent::FormEdit($page);
    }
    
    public function GetFormEdit($args=array())
    {
        $geolocation = isset($args[$this->GetAttrName()]) ? $args[$this->GetAttrName()] : array(
            'latitude' => get_request_gp('latitude-'.$this->GetAttrName(), '', true),
            'longitude' => get_request_gp('longitude-'.$this->GetAttrName(), '', true)
        );
        return $geolocation;
    }
    
    public function SaveFormEdit($page_id, $args=array(), $lang=null)
    {
        return Page::InsertUpdateMeta($page_id, $this->GetAttrName(), $this->GetFormEdit($args), $lang);
    }
    
    
    
}
