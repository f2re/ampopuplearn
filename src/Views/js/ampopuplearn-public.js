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

		datefrom:'',
		dateto:'',

		// sort params
		sort:'name',
		reversesort:false,

	  },

	  computed:{
		//   calc students, sort, arrange and filter
		students_prepared:function(){
			let _vue = this;
			let prepared = _vue.students;
			
			// realize filter function
			if ( _vue.datefrom!='' && _vue.dateto!='' ){
				let dt = new Date(_vue.datefrom);
				let dt_to = new Date(_vue.dateto);
				
				prepared = prepared.filter( obj => {
					const [ month, day, year] = obj.startDate.split("-");
					let _dt = new Date(year, month - 1, day);
					return _dt>=dt && _dt<=dt_to;
				} );
			}

			// realize sort function
			function customsort(a, b) {
				if (a[_vue.sort] < b[_vue.sort]) return _vue.reversesort?-1:1;
				if (a[_vue.sort] > b[_vue.sort]) return _vue.reversesort?1:-1;
				return 0;
			 }
			 // return this.users.sort(surnameName); // sorts in-place
			 return [...prepared].sort(customsort); // shallow clone + sort
		}
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
		  },
		  /**
		   * set sort method
		   * @param {} label 
		   */
		  setSort(label){
			if ( this.sort==label ){
				this.reversesort = !this.reversesort;
			}else{
				this.sort=label;
				this.reversesort=false;
			}
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
  