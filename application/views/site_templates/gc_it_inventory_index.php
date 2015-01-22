<h3><?=$pageTitle?></h3>
<?=$output?>
<?if ($msg!=null){
    foreach ($msg as $value ) {
        echo str_replace('%20',' ',$value);
    }
}

?>

<!-- Code to handle the server response (see test.php) -->

<script language="JavaScript">        
       
	$(document).ready(function(e){
    	$("#collapseOne").removeClass("in").addClass("in");
		$("#collapseTwo").removeClass("in");

       /* var msg = null;
        msg=<?=$msg?>;
        if(msg!=null){
            $("#msg").show();
            }*/

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
