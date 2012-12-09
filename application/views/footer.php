<footer>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-10968174-14']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
  <script type='text/javascript' src="http://code.jquery.com/jquery-latest.js" ></script>
  <script type='text/javascript'>
    $(document).ready(function(){
		mobileStyleUpdates();
		
		$(window).resize(function(){ mobileStyleUpdates(); });
	});
	function mobileStyleUpdates()
	{
		$width = $(window).width();
		$height = $(window).height();
		
		if($width < 350 || $height < 500)
		{
			$("h1").addClass("small");
			$("h2").addClass("small");
		}
		else 
		{
			$("h1").removeClass("small");
			$("h2").removeClass("small");
		}	
	}
    function signUpPostback()
    {
      var form = document.forms["signUpForm"];

      $.post("<?=base_url();?>/login/signUpUser", { 
        access_login : form.elements["access_login"].value,
        username : form.elements["username"].value,
        access_password : form.elements["access_password"].value,
        access_password_verification : form.elements["access_password_verification"].value
        }, function(data)
        {
            $(".formMessage").html(data).css({"color":"#FFE130", "fontSize":"28px", "border":"2px solid #FFD82D", "padding":"10px" });
        });
      
      return false;  
    }
  </script>
</footer>
</body>
</html>