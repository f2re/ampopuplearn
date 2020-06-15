( function() {	

	/**
	 * Vue main component 
	 * AmpopMusic plugin
	 */

	var vm = new Vue({
	  el: document.querySelector('#ampopmusiclearn'),
	  mounted: function(){
		console.log("Hello Vue!");
	  }	  
	});

	import Slider from '../src/Components/Slider';
	Vue.component('slider', Slider)
	
})();
  