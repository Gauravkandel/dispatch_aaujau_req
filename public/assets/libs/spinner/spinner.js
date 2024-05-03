/**
 * custom spinner Js 
 */
const spinnerJS = {
    showSpinner() {
        //$('.nb-spinner-main').addClass('show-nb-spinner-main');
        $('.header_spinner').show();
    },
    hideSpinner(){
        setTimeout(() => {
            //$('.nb-spinner-main').removeClass('show-nb-spinner-main');
            $('.header_spinner').hide();
        },800);
    }
}

document.onreadystatechange = function () {
    var state = document.readyState
    if (state == 'interactive') {
        spinnerJS.showSpinner();
    } else if (state == 'complete') {
        spinnerJS.hideSpinner();
    }
}