    var marker;
    var map;
    if( (typeof lat).toString() != 'undefined' || (typeof lng ).toString() != 'undefined'  ){
        var myPos = new GLatLng( lat , lng  );
    }
    if( (typeof clat).toString() != 'undefined' || (typeof clng ).toString() != 'undefined'  ){
        var centered   = new GLatLng( clat , clng );
    }

    function setMapPosition( ){
        var mpc = map.getCenter();
        zoom    = map.getZoom();
        clat = mpc.lat();
        clng = mpc.lng();
        sendMapMeta( );
    }

    function sendMapMeta( ){
        if( (typeof id).toString() == 'undefined' ){
            return 0;
        }
        if( (typeof title).toString() == 'undefined' ){
            var title = '';
        }
        if( (typeof desc).toString() == 'undefined' ){
            var desc = '';
        }
        if( (typeof phone1).toString() == 'undefined' ){
            var phone1 = '';
        }
        if( (typeof phone2).toString() == 'undefined' ){
            var phone2 = '';
        }
        if( (typeof fax).toString() == 'undefined' ){
            var fax = '';
        }
        if( (typeof email).toString() == 'undefined' ){
            var email = '';
        }
        if( (typeof message).toString() == 'undefined' ){
            var message = '';
        }
        if( (typeof hidde_contact ).toString() == 'undefined' ){
            var hidde_contact = '';
        }
        jQuery( document ).ready(function(){
            jQuery.get(
                ajaxurl , {
                "action" : 'set_map_meta' ,
                "id" : id,
                "lat" : lat,
                "lng" : lng,
                "clat": clat,
                "clng": clng,
                "zoom": zoom,
                "map_title": title,
                "map_description": desc,
                "map_phone1": phone1,
                "map_phone2": phone2,
                "map_fax": fax,
                "map_email": email,
                "message": message,
                "hidde_contact" : hidde_contact }, function( result ){
                    jQuery('div#map_meta').html( result );
                } );
        });
    }

    function getMarkerPosition( ){
        lat = marker.getLatLng().lat();
        lng = marker.getLatLng().lng();
        marker.openInfoWindowHtml( info );
        sendMapMeta( );
    }

    function initialize() {
        
        map = new GMap2( document.getElementById("map_canvas") );
        map.setCenter( centered , zoom );
        map.addControl(new GLargeMapControl3D());
        map.addControl(new GMenuMapTypeControl());
        map.enableDragging();
        map.enableContinuousZoom();
        map.enableDoubleClickZoom();
        map.enableScrollWheelZoom();


        marker = new GMarker( myPos ,{ draggable:true });

        GEvent.addListener(marker,"mouseup", getMarkerPosition );
        GEvent.addListener(map, 'zoomend' , setMapPosition );
        GEvent.addListener(map, 'moveend' , setMapPosition );
        

        
        marker.openInfoWindowHtml( info );
        map.addOverlay( marker );
    }

    function addTitleMap( val ){
        h_title = '<h3>' + val + '</h3>';
        title = val;
        info    =  h_title + h_desc + h_phone1 + h_phone2 + h_fax + h_email;
        marker.openInfoWindowHtml( info );
        map.addOverlay(marker);
    }

    function addDescriptionMap( val ){
        h_desc = '<p>' + val + '</p>';
        desc = val
        info    =  h_title + h_desc + h_phone1 + h_phone2 + h_fax + h_email;
        marker.openInfoWindowHtml( info );
        map.addOverlay(marker);
    }

    function addPhone1Map( val ){
        h_phone1 = '<small>' + f_phone1 + val + '</small><br />';
        phone1 = val;
        info    =  h_title + h_desc + h_phone1 + h_phone2 + h_fax + h_email;
        marker.openInfoWindowHtml( info );
        map.addOverlay(marker);
    }

    function addPhone2Map( val ){
        h_phone2 = '<small>' + f_phone2 + val + '</small><br />';
        phone2 = val;
        info    =  h_title + h_desc + h_phone1 + h_phone2 + h_fax + h_email;
        marker.openInfoWindowHtml( info );
        map.addOverlay(marker);
    }

    function addFaxMap( val ){
        h_fax = '<small>' + f_fax + val + '</small><br />';
        fax = val;
        info    =  h_title + h_desc + h_phone1 + h_phone2 + h_fax + h_email;
        marker.openInfoWindowHtml( info );
        map.addOverlay(marker);
    }

    function addEmailMap( val ){
        h_email = '<small>' + f_email + val + '</small><br />';
        email = val;
        info    =  h_title + h_desc + h_phone1 + h_phone2 + h_fax + h_email;
        marker.openInfoWindowHtml( info );
        map.addOverlay(marker);
    }

    function loadScript() {
        var script = document.createElement("script");
        script.type = "text/javascript";
        script.src = "http://maps.google.com/maps/api/js?sensor=false&callback=initialize";
        document.body.appendChild(script);
    }

    if( (typeof lat).toString() != 'undefined' || (typeof lng ).toString() != 'undefined'  ){
        window.onload = loadScript;
    }