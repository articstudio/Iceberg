<?php

define('APIWS_DIR', dirname( __FILE__ ) . DIRECTORY_SEPARATOR);
define('APIWS_URL', get_file_url(APIWS_DIR, ICEBERG_DIR, get_base_url()));

require APIWS_DIR . 'functions.php';


/**** API ****/
if (in_api())
{
    define('APIWS_API_ENVIRONMENT', 'aws');
    
    function iceberg_api_generate_apiws($args)
    {
        $module = get_request_module();
        if ($module === 'info')
        {
            //require_once DEFAULT_ADMIN_THEME_DIR . 'elfinder/popup-ckeditor.php';
        }
        else if ($module === 'bbbbbbbbbbb')
        {
            //require_once DEFAULT_ADMIN_THEME_DIR . 'elfinder/popup.php';
        }
        else
        {
            Request::Response(400);
        }
        exit();
    }
    
    function iceberg_api_generate_apiws_poblacions($args)
    {
        $lang = get_language_default();
        $return = array(
            'status' => 'ERROR',
            'data' => array()
        );
        $module = get_request_module();
        if ($module === 'info')
        {
            //INFO
        }
        else
        {
            $latitud = get_request_gp('latitud', false, true);
            $longitud = get_request_gp('longitud', false, true);
            $pages = get_pages(array(
                'taxonomy' => get_theme_taxonomy('city'),
                'status' => 1,
                'order' => 'name',
                'metas' => array(
                    'active' => 1
                )
            ), $lang);
            $_geo = false;
            if ($latitud && $longitud)
            {
                $_geo = true;
                foreach ($pages AS $page_id => $page)
                {
                    $pages[$page_id]->apiws_poblacio_distancia = -1;
                    $geo = array_filter($page->GetMeta('geolocation'));
                    //var_dump($geo);
                    if (!empty($geo))
                    {
                        //var_dump($geo);
                        //CALC
                        $distance = coordinatesDistance((float)$latitud, (float)$longitud, (float)$geo['latitude'], (float)$geo['longitude']);
                        $pages[$page_id]->apiws_poblacio_distancia = round($distance, 2);
                    }
                    //echo '-------------------'."\n";
                }
            }
            
            $arr = array();
            foreach ($pages AS $page_id => $page)
            {
                $arr[] = array(
                    'id' => $page_id,
                    'titol' => cleanStringHTML($page->GetTitle()),
                    'distancia' => $_geo ? $page->apiws_poblacio_distancia : -1
                );
            }
            uasort($arr, 'orderByDisatnce');
            $return['status'] = 'ok';
            $return['data'] = $arr;
        }
        header('Content-type: application/json');
        $json = json_encode($return);
        print $json;
        exit();
    }
    
    function iceberg_api_generate_apiws_sectors($args)
    {
        $lang = get_language_default();
        $return = array(
            'status' => 'ERROR',
            'data' => array()
        );
        $module = get_request_module();
        if ($module === 'info')
        {
            //INFO
            /**
             * ==== SECTORS  ====
             * 2 => Què puc fer
             * 3 => Racons per descobrir
             * 4 => Gaudeix del comerç
             * 5 => Allotjament i restauració
             */
        }
        else
        {
            $id_poblacio = (int)get_request_gp('id_poblacio', false, true);
            $id_categoria = (int)get_request_gp('id_categoria', false, true);
            
            if ($id_poblacio && $id_categoria)
            {
                $assoc = get_pages(array(
                    'taxonomy' => get_theme_taxonomy('association'),
                    /*'status' => 1,*/
                    'parent' => $id_poblacio
                ), $lang);
                $assoc_ids = array_keys($assoc);
                $shop_parents = array_merge($assoc_ids, array($id_poblacio));

                $args_categs = array(
                    'taxonomy' => get_theme_taxonomy('categ'),
                    'status' => 1,
                    'parent' => $id_categoria,
                    'order' => 'name'
                );
                $categs = get_pages($args_categs, $lang);


                foreach ($categs AS $categ_id => $categ)
                {
                    $pages = get_pages(array(
                        'taxonomy' => array(get_theme_taxonomy('shop')),
                        'status' => 1,
                        'parent' => $shop_parents,
                        'metas' => array(
                            'category' => '"' . $categ->id . '"'
                        )
                    ), $lang);
                    $n = count($pages);

                    if ($n>0)
                    {
                        $return['data'][] = array(
                            'id' => $categ_id,
                            'titol' => cleanStringHTML($categ->GetTitle()),
                            'contador' => $n
                        );
                    }
                }
                $return['status'] = 'ok';
            }
        }
        
        header('Content-type: application/json');
        $json = json_encode($return);
        print $json;
        //exit();
    }
    
    function iceberg_api_generate_apiws_locals($args)
    {
        $lang = get_language_default();
        $return = array(
            'status' => 'ERROR',
            'data' => array()
        );
        $module = get_request_module();
        if ($module === 'info')
        {
            //INFO
            /**
             * ==== SECTORS  ====
             * 2 => Què puc fer
             * 3 => Racons per descobrir
             * 4 => Gaudeix del comerç
             * 5 => Allotjament i restauració
             */
        }
        else
        {
            $latitud = get_request_gp('latitud', false, true);
            $longitud = get_request_gp('longitud', false, true);
            $id_poblacio = (int)get_request_gp('id_poblacio', false, true);
            $id_categoria = (int)get_request_gp('id_categoria', false, true);
            $id_sector = (int)get_request_gp('id_sector', false, true);
            $promo = (bool)get_request_gp('promo', 0);
            $page = (int)get_request_gp('page', 1);
            $items_page = (int)get_request_gp('items_per_page', 10);
            
            $pages_args = array(
                'taxonomy' => array(
                    get_theme_taxonomy('shop')
                ),
                'status' => 1,
                'order' => 'name',
                'relateds' => array(),
                'metas' => array()
            );
            if ($id_poblacio)
            {
                $assoc = get_pages(array(
                    'taxonomy' => get_theme_taxonomy('association'),
                    /*'status' => 1,*/
                    'parent' => $id_poblacio
                ), $lang);
                $assoc_ids = array_keys($assoc);
                $shop_parents = empty($assoc_ids) ? $id_poblacio : array_merge($assoc_ids, is_null($id_poblacio) ? array() : array($id_poblacio));
                $pages_args['parent'] = $shop_parents;
            }
            if ($id_sector)
            {
                $pages_args['relateds']['category'] = $id_sector;
            }
            if ($promo)
            {
                $pages_args['metas']['offer_title'] = Page::FIELD_NOT_EMPTY;
            }
            //var_dump($pages_args);
            $pages = get_pages($pages_args, $lang);
            $n_pages= count($pages);
            $items = array();
            
            if ($n_pages <= $items_page)
            {
                if ($page === 1)
                {
                    $items = $pages;
                }
            }
            else
            {
                $items = array_slice($pages, ($page - 1) * $items_page, $items_page, true);
            }
            
            $return['page'] = $page;
            $return['items_per_page'] = $items_page;
            $return['total_items'] = count($pages);
            $return['pages'] = ceil($return['total_items'] / $return['items_per_page']);
            foreach ($items AS $item_id => $item)
            {
                $offer_title = $item->GetMeta('offer_title');
                $offer_description = $item->GetMeta('offer_description');
                $offer_images = $item->GetMeta('offer_images');
                $offer_date_end = $item->GetMeta('offer_date_end');
                if (!$promo || ((!empty($offer_title) || !empty($offer_description)) && isValidOfferDate($offer_date_end)))
                {
                    $geo = array_filter($item->GetMeta('geolocation'));
                    $distancia = -1;
                    if ($latitud && $longitud && !empty($geo) && isset($geo['latitude']) && isset($geo['longitude']))
                    {
                        $distancia = round(coordinatesDistance($latitud, $longitud, $geo['latitude'], $geo['longitude']), 2);;
                    }

                    $return['data'][] = array(
                        'id' => $item_id,
                        'titol' => cleanStringHTML($item->GetTitle()),
                        'descripcio' => cleanStringHTML($item->GetText()),
                        'adreca' => cleanStringHTML($item->GetMeta('address')),
                        'codi_postal' => cleanStringHTML($item->GetMeta('zipcode')),
                        'img' => $item->GetImage(),
                        'img1' => $item->GetImage(),
                        'img2' => $item->GetImage(),
                        /*'img1' => $item->GetMeta('image_mobile_1'),
                        'img2' => $item->GetMeta('image_mobile_2'),*/
                        'coordenades' => $geo,
                        'telefon' => cleanStringHTML($item->GetMeta('phone')),
                        'distancia' => $distancia,
                        'email' => cleanStringHTML($item->GetMeta('email')),
                        'web' => cleanStringHTML($item->GetMeta('web')),
                        'horari' => cleanStringHTML($item->GetMeta('schedule')),
                        'oferta' => ((!empty($offer_title) || !empty($offer_description)) && isValidOfferDate($offer_date_end)),
                        'oferta_titol' => cleanStringHTML($offer_title),
                        'oferta_descripcio' => cleanStringHTML($offer_description),
                        'oferta_img1' => isset($offer_images[0]) ? $offer_images[0]['image'] : '',
                        'oferta_img2' => isset($offer_images[1]) ? $offer_images[1]['image'] : ''
                    );
                }
            }
            $return['status'] = 'ok';
        }
        header('Content-type: application/json');
        $json = json_encode($return);
        print $json;
        exit();
    }
    
    
    function apiws_iceberg_api_generate_init($args)
    {
        $arr = array(
            APIWS_API_ENVIRONMENT => 'iceberg_api_generate_apiws',
            'poblacions' => 'iceberg_api_generate_apiws_poblacions',
            'sectors' => 'iceberg_api_generate_apiws_sectors',
            'locals' => 'iceberg_api_generate_apiws_locals'
        );
        
        //view-source:http://iceberg.servei-hosting.com/api/poblacions/?latitud=1.2&longitud=1.2
        //http://iceberg.servei-hosting.com/api/sectors/?id_poblacio=18&id_categoria=4
        //view-source:http://iceberg.servei-hosting.com/api/locals/
        
        foreach ($arr AS $env => $callback)
        {
            if (RoutingAPI::InEnvironment($env))
            {
                do_action($callback, array());
                break;
            }
        }
    }
    add_action('iceberg_api_generate', 'apiws_iceberg_api_generate_init', 0);
    
    
}




/**
 * Calculates the great-circle distance between two points, with
 * the Haversine formula.
 * @param float $latitudeFrom Latitude of start point in [deg decimal]
 * @param float $longitudeFrom Longitude of start point in [deg decimal]
 * @param float $latitudeTo Latitude of target point in [deg decimal]
 * @param float $longitudeTo Longitude of target point in [deg decimal]
 * @param float $earthRadius Mean earth radius in [m]
 * @return float Distance between points in [m] (same as earthRadius)
 */
function coordinatesDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6372797)
{
    /*
    $latFrom = deg2rad($latitudeFrom);
    $lonFrom = deg2rad($longitudeFrom);
    $latTo = deg2rad($latitudeTo);
    $lonTo = deg2rad($longitudeTo);

    $latDelta = $latTo - $latFrom;
    $lonDelta = $lonTo - $lonFrom;

    $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
    return $angle * $earthRadius;
    */
    
    $latFrom = deg2rad($latitudeFrom);
    $lonFrom = deg2rad($longitudeFrom);
    $latTo = deg2rad($latitudeTo);
    $lonTo = deg2rad($longitudeTo);

    $lonDelta = $lonTo - $lonFrom;
    $a = pow(cos($latTo) * sin($lonDelta), 2) +
    pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
    $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

    $angle = atan2(sqrt($a), $b);
    return $angle * $earthRadius;
    
    /*
    $pi80 = M_PI / 180;
	$latitudeFrom *= $pi80;
	$longitudeFrom *= $pi80;
	$latitudeTo *= $pi80;
	$longitudeTo *= $pi80;
 
	$dlat = $latitudeTo - $latitudeFrom;
	$dlng = $longitudeTo - $longitudeFrom;
	$a = sin($dlat / 2) * sin($dlat / 2) + cos($latitudeFrom) * cos($latitudeTo) * sin($dlng / 2) * sin($dlng / 2);
	$c = 2 * atan2(sqrt($a), sqrt(1 - $a));
	$m = $earthRadius * $c;
 
	return $m;
    */
}

function orderByDisatnce($a, $b)
{
    $an = $a['distancia'];
    $bn = $b['distancia'];
    if ($an == $bn) {
        return 0;
    }
    if ($an < 0)
    {
        return 1;
    }
    if ($bn < 0)
    {
        return -1;
    }
    return ($an < $bn) ? -1 : 1;
}

