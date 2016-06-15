<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>NDAR dictionary</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>

   <!--  <link href="/css/bootstrap-responsive.css" rel="stylesheet">-->

    
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="/img/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/img/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/img/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="/img/apple-touch-icon-57-precomposed.png">
    <link rel="shortcut icon" href="/img/favicon.png">

    <style>
      .Published {
         background-color: lightgray;
      }
      .Archived {
         background-color: MistyRose;
      }
    </style>

  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="navbar-brand" href="#">NDAR data dictionary resource</a>
	  <div class="navbar-form navbar-right" role="search">
            <div class="form-group">
              <input type="text" id="searchAllVal" class="form-control" placeholder="Search">
            </div>
            <button type="submit" href="#" id="searchAll" class="btn btn-default">Magic</button>
	  </div>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="row">
         <h4>Available categories</h4>
         <div id="categories"></div>
      </div>
    </div>
    <hr>
    <div class="container">
      <div class="row">
         <h4>Available data structures <span class="small dataname"></span></h4>
         <div id="datastructures"></div>
      </div>
    </div>
    <hr>
    <div class="container">
      <div class="row">
         <h4>Known measures <span class="small measurename"></span></h4>
         <div id="content"></div>
      </div>
    </div> <!-- /container -->
    
    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//code.jquery.com/jquery-2.1.3.min.js"></script>
    <script src="//code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script>

    <!-- Bootstrap JS and Bootstrap Image Gallery are not required, but included for the demo -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>

    <script type="text/javascript">
      var data;

      jQuery(document).ready(function() {

        jQuery('#searchAll').click(function() {
           var term = jQuery('#searchAllVal').val();
           console.log("search for this" + term );
           // now we troll the whole NDA dictionary
        });


        jQuery('#datastructures').on('click', '.entry', function() {
           jQuery('#content').children().remove();
           var v = jQuery(this).find('span.shortName').text();
           jQuery('.measurename').text(v);
           jQuery.getJSON('NDAR_datastructures.php?entry='+v, function(data) {
              // console.log("got some data");
              jQuery.each(data.dataElements, function(index, value) {
                var n = "normal";
                if (data.status == "Archived") {
                   n = " (archived, don't use)";
                }
                jQuery('#content').append('<div class="vals col-xs-3">' + 
	           '<a href="https://ndar.nih.gov/ndar_data_dictionary.html?short_name='+ v + '">' + '<span class="glyphicon glyphicon-hand-right" aria-hidden="true"></span></a> <span class="title">' + value.name + '</span>'+n+'<br/><span class="description"><i>' + value.description + '</i></span></div>');
              });
           });
        });

        jQuery('#categories').on('click', '.category', function() {
           var entries = jQuery('#datastructures').children();
           var e = this.innerHTML;
           jQuery('.dataname').text(e);
           jQuery('#content').children().remove();
           for (var i = 0; i < entries.length; i++) {
              var value = entries[i];
              var cat = jQuery(value).find('span.category').text();
              if (cat != this.innerHTML) {
                jQuery(value).hide();
              } else {
                jQuery(value).show();
              }
           }
        });

        jQuery.getJSON('NDAR_datastructures.php', function(data) {
          ndardata = data;
          // how many categories?
          var cat = {};
          jQuery.each(data, function(index, value) {
            jQuery.each(value.categories, function(i,v) {
               cat[v] = 1;
            });
          });
          cat = Object.keys(cat);
          cat = cat.sort();
          // add the categories first
          jQuery.each(cat, function(index, value) {
             jQuery('#categories').append('<div class="col-xs-2 category">' + value + '</div>');
          });

          jQuery.each(data, function(index, value) {
             jQuery('#datastructures').append('<div class="col-xs-2 entry"><span class="nr">' + index + '</span>: <span class=\"shortName '+ value.status +'\" ndarURL=\"'
		+ value.ndarURL+ '">' + value.shortName + '</span><br/><span class="title">'+ value.title+'</span>' +
	        '<span class="category">'+value.categories[0]+'</span></div>');
          });

        });

      });

    </script>

  </body>
</html>
