// This file contains custom JavaScript for the website, handling interactivity and dynamic content.

// Document ready function to ensure the DOM is fully loaded before executing scripts
$(document).ready(function() {
    // Example: Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Example: Handle form submissions with AJAX
    $('form.ajax-form').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        var form = $(this);
        var url = form.attr('action'); // Get the action URL from the form

        $.ajax({
            type: form.attr('method'), // Get the method (POST/GET) from the form
            url: url,
            data: form.serialize(), // Serialize the form data
            success: function(response) {
                // Handle success response
                alert('Form submitted successfully!');
                // Optionally, you can update the UI or redirect
            },
            error: function(xhr, status, error) {
                // Handle error response
                alert('An error occurred: ' + error);
            }
        });
    });

    // Example: Dynamic content loading
    $('#load-content').on('click', function() {
        $.get('api/get_barang.php', function(data) {
            $('#content-area').html(data); // Load data into a specific area
        });
    });
});