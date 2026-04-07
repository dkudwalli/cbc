document.addEventListener('DOMContentLoaded', function() {
    // Function to get URL parameter
    function getUrlParameter(name) {
        name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
        var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
        var results = regex.exec(location.search);
        return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
    }

    // Get the booking ID from URL
    var bookingId = getUrlParameter('allow');

    if (bookingId) {
        // Find the matching divs
        var registrantDiv = document.querySelector('#registrant-' + bookingId);
        var expandableDiv = document.querySelector('#registrant-details-' + bookingId);

        if (registrantDiv) {
            // Add the active class
            registrantDiv.classList.add('active');

            // Scroll to the active div
            registrantDiv.scrollIntoView({ behavior: 'smooth' });
        }

        if (expandableDiv) {
            // Remove the display: none style
            expandableDiv.style.removeProperty('display');
        }
    }

    // Event listener for clicking on registrant details div
    document.querySelectorAll('.eventer-admin-registrant-details').forEach(function(item) {
        item.addEventListener('click', function() {
            var id = this.id.replace('registrant-', '');
            var expandable = document.querySelector('#registrant-details-' + id);

            if (expandable) {
                if (expandable.style.display === 'none') {
                    expandable.style.removeProperty('display');
                } else {
                    expandable.style.display = 'none';
                }
            }
        });
    });
});
