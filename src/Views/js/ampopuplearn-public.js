( function() {
	/**
	 * Vue main component 
	 * AmpopMusic plugin
	 */

	var vm = new Vue({
	  el: '#ampopmusiclearn',
	  data:{
		// urls array
		urls:{
			students: '/wp-json/',
			// get list of courses
			courses: '/wp-json/ldlms/v1/sfwd-courses',
			students: '/wp-admin/admin-ajax.php',

		},

		// search query variable
		query:'',
		// active (selected) music category
		active_category:'',
		// Array of students in selected category
		students:[],

	  },
	  mounted: function(){
		console.log("Hello Vue!");

		// fetch( '/wp-json/ampopmusic/v1/report' ).then(response => console.log(response));


		jQuery.ajax({
			type: "POST",
			url: '/wp-admin/admin-ajax.php?_fs_blog_admin=true',
			dataType: "json",
			cache: false,
			data: {
				'action': 'ampopmusic_getreport',
				'userid': ampoppublic_params.userid,
				'data': {
					'init': 1,
					'nonce': '5ba39ba0b8',
					'slug': 'user-courses',
				} },
			error: function(jqXHR, textStatus, errorThrown ) {
			},
			success: function(reply_data) {
				console.log(reply_data)
			}
		});
		return;

		fetch( '/wp-admin/admin-ajax.php',{
			method: 'POST',
			body: JSON.stringify({
				'action': 'learndash_propanel_reporting_get_result_rows',
				// 'nonce' : ld_propanel_settings.nonce,
				// 'filters' : currentFilters,
				'container_type' : 'widget' })
		} ).then( response => console.log(response));

	  }
	});
})();
  