jQuery(document).ready(function($) {
	// Get the current page number from the URL using a regular expression
	const pageMatch = window.location.pathname.match(/page\/(\d+)/);
	const page = pageMatch ? parseInt(pageMatch[1]) : 1; // Default to 1 if no match
	const itemsPerPage = 10; // Adjust this based on your actual items per page
	const startCounter = (page - 1) * itemsPerPage + 1;

	// Set the initial counter value
	$('body').css('counter-reset', 'sermon ' + (startCounter - 1));
});