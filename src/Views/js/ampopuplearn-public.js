window.onload = function () {

	var bus = new Vue();

	Vue.component('vue-swiper', {
		data: function() {
			return {
			   imageItems:[],
			   infinite: true,
			}
		},
		props:['url','active_category'],
			mounted: function () {
			var amSwiper = new Swiper ('.swiper__container', {
				centeredSlides: false,
				direction: 'horizontal',
				loop: this.infinite,
				nextButton: '.swiper-button-next',
				prevButton: '.swiper-button-prev',
				slidesPerView: 6,
			});
		},
		methods: {
			setCategory(id){
				bus.$emit('setCategory',id);
				// console.log(id);
			},
			beforeMount(){
				this.setCategory(5)
			}
		},		
	});

	/**
	 * Vue main component 
	 * AmpopMusic plugin
	 */

	var vm = new Vue({
	  el: '#ampopmusiclearn',
	  data:{
		// urls array
		urls:{
			// students: '/wp-json/',
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

		// group ID 
		groupid: false,

		// sort params
		sort:'name',
		reversesort:false,

	  },

	  computed:{
		//   calc students, sort, arrange and filter
		students_prepared:function(){
			let _vue = this;
			let prepared = _vue.students;
			
			
			// filter by courses
			if ( _vue.active_category>0 && prepared.length>0 ){
				prepared = prepared.filter( obj => {
					if ( obj.courseprogress.indexOf( _vue.active_category ) !== -1 ){
						return true;
					}
				} );
			}
			
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
		/**
		 * set category filter
		 * @param {*} id 
		 */
		setCategory(id){
			if ( this.active_category == id ){
				this.active_category = '';
			}else{
				this.active_category = parseInt(id);
			}
			// console.log("asdf");
			// console.log(id);
		},

		/**
		 * Get color of percentage bar
		 * @param {} percent 
		 */
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
		},

		/**
		 * loading users by category id
		 */
		loadUsers(){
			let _vue = this;
			fetch( '/wp-admin/admin-ajax.php',{
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8'
				},
				credentials: 'same-origin',
				body: 'action=ampopmusic_getreport&category_id='+_vue.groupid,
			} ).then((response) => {
				return response.json();
			  }).then( data => {
				// save Students
				// console.log(_vue.students);
				_vue.students = data.data;
				// save active category
				// _vue.active_category = data.category_id;
			});
		},
	  },
	  mounted: function(){
		let _vue = this;
		
		_vue.loadUsers();
		return;

	  },
	  //   emit on created component
	  created(){
		//   bind bus emit when click on button
		bus.$on('setCategory',this.setCategory);
	  }
	});
};
  