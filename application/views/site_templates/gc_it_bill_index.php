<h3><?=$pageTitle?></h3>

<?=$output?>
<!-- Code to handle the server response (see test.php) -->

<script language="JavaScript">        
       
	$(document).ready(function(e){
    	$("#collapseOne").removeClass("in").addClass("in");
		$("#collapseTwo").removeClass("in");
        var url = $('.datatables-add-button').children().attr('href')
        url = url.replace('index', 'add_Bill')
        //url = url.replace('/add', '')
        $(".datatables-add-button").children().attr("href", url)

		$("a#approve_modal").click(function() {            
            var href = $(this).attr('href');
			var hrefLoc = $(this).attr('href');
            href = href.substr(href.lastIndexOf('/'));
            show_approve_info(hrefLoc);
            return false;
        });
		                
	});       
    function show_approve_info(href){            
         $.fancybox({
            "href" : href
         });         
    }   
</script>
