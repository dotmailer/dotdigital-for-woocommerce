(function(w,d,u,t,o,c){w['dmtrackingobjectname'] = o;c = d.createElement( t );c.async = 1;c.src = u;t = d.getElementsByTagName( t )[0];t.parentNode.insertBefore( c,t );w[o] = w[o] || function(){(w[o].q = w[o].q || []).push( arguments );};
})( window, document, '//static.trackedweb.net/js/_dmptv4.js', 'script', 'dmPt' );

window.dmPt( 'create', wbt_data.profile_id );

if (typeof product_data !== 'undefined') {
  window.dmPt('track', product_data.data || {});
} else {
  window.dmPt('track');
}
