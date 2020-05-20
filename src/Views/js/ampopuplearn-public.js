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
	  }
	});
})();
  