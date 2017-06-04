</div><br><br>
	
	<!--FOOTER-->
	<footer class="text-center" id="footer">&copy; Copyright 2017 Din Design</footer>


	<!--DETAILS MODAL-->
	

	<script>
		jQuery(window).scroll(function(){
			var vscroll = jQuery(this).scrollTop();
			jQuery('#logoText').css({
				"transform" : "translate(0px, "+vscroll/2.3+"px)"
			});
		});

		function detailsmodal(id){
			var data = {"id":id};
			jQuery.ajax({
				url: '/WebShop/includes/detailsModal.php', 
				method : "post",
				data : data,
				success : function(data){
					jQuery('body').append(data);
					jQuery('#details-modal').modal('toggle');
				},
				error : function(){
					alert("Something went wrong!");
				}
			});
		}
	</script>
		
	</body>
</html>