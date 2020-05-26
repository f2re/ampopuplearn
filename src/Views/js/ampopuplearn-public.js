window.onload = function () {
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
	  methods: {
		  getColorOfPercent(percent){
			  var result = [];
			  if ( percent<40 ){
				result.push('red');
			  }else
			  if ( percent<60 ){
				result.push('blue');
			  }else{
				result.push('green');				  
			  }
			  return result;
		  }
	  },
	  mounted: function(){
		let _vue = this;
		fetch( '/wp-admin/admin-ajax.php',{
			method: 'POST',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8'
			},
			credentials: 'same-origin',
			body: 'action=ampopmusic_getreport&category_id='+"123",
		} ).then((response) => {
			return response.json();
		  }).then( data => {
			// save Students
			_vue.students = data.data;
			console.log(_vue.students);
			// save active category
			_vue.active_category = data.category_id;
		});

		return;

	  }
	});
};
  