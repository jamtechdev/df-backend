// Ensure loader stays at least 500ms (or adjust here)
window.addEventListener('load', function () {
    setTimeout(function () {
        const loader = document.getElementById('globalLoader');
        if (loader) {
            loader.style.display = 'none';
        }
    }, 500); // 500ms delay
});

// Ajax Loader Handling
$(document).on({
    ajaxStart: function () {
        $('#globalLoader').show();
    },
    ajaxStop: function () {
        // same delay for AJAX loader hide
        setTimeout(function () {
            $('#globalLoader').hide();
        }, 500); // adjust delay if needed
    }
});

$(document).ready(function () {
    // Show sidebar on button click (hamburger)
    $('#toggleSidebarBtn').on('click', function () {
        $('#layout-menu').addClass('sidebar-visible');
        $('body').addClass('sidebar-visible');
    });

    // Hide sidebar on 'X' button click
    $('#closeSidebarBtn').on('click', function () {
        $('#layout-menu').removeClass('sidebar-visible');
        $('body').removeClass('sidebar-visible');
    });


});


// Date formatting function (YYYY-MM-DD)
function formatDate(dateString) {
    if (!dateString) return '';
    let date = new Date(dateString);
    return !isNaN(date) ? date.toISOString().slice(0, 10) : '';
}

