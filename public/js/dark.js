const currentTheme = localStorage.getItem('theme');

if (currentTheme === 'dark') {
    $("#body").addClass("dark-mode");
    $("#dark-icon").html('<i class="fas fa-moon"></i>');
    
} else {
    $("#body").removeClass("dark-mode");
    $("#dark-icon").html('<i class="far fa-moon"></i>');
}

$("#check-dark").click(function(){

    if ($('input#check-dark').is(':checked')) {
        $("#body").addClass("dark-mode");
        $("#dark-icon").html('<i class="fas fa-moon"></i>');
        localStorage.setItem('theme', 'dark')
    } else {
        $("#body").removeClass("dark-mode");
        $("#dark-icon").html('<i class="far fa-moon"></i>');
        localStorage.setItem('theme', 'light')
    }
})