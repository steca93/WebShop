</div><br><br>
	
	<!--FOOTER-->
	<footer class="text-center" id="footer">&copy; Copyright 2017 Din Design

	<script>

	function updateSizes(){
		var sizeString = '';
		for (var i = 1; i < 12; i++) {
			if (jQuery('#size'+i).val()!= '') {
				sizeString += jQuery('#size'+i).val()+':'+jQuery("#qty"+i).val()+',';
			}
		}
		jQuery("#sizes").val(sizeString);
	}



	//AJAX SKRIPTA ZA VRACANJE CHILD ELEMENATA KADA SE IZABERE PARENT(ADD PRODUCT)
		function get_child_options(){
			var parentID = jQuery('#parent').val();
			jQuery.ajax({
				url: '/WebShop/admin/parsers/child_categories.php',
				type: 'POST', 
				data: {parentID :parentID}, 
				success: function(data){
					jQuery('#child').html(data);
				}, 
				error: function(){alert("Something went wrong with the child options")},
			});
		}
		jQuery('select[name="parent"]').change(get_child_options);
	</script>
	
	

	</footer>
	</body>
</html>