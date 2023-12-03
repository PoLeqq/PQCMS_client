window.addEventListener('scroll', function() {
	var element = document.querySelector('#main-container');
	var position = element.getBoundingClientRect();

	// checking whether fully visible
	if(position.top >= 0 && position.bottom <= window.innerHeight) {
		console.log('Element is fully visible in screen');
	}

	// checking for partial visibility
	if(position.top < window.innerHeight && position.bottom >= 0) {
		console.log('Element is partially visible in screen');
	}
});