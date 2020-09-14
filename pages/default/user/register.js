/**
 * @file register.js
 */






function showLoader() {
    $('[role="submit"]').hide();
    $('[role="loader"]').show();
}
function hideLoader() {
    $('[role="submit"]').show();
    $('[role="loader"]').hide();
}
/**
 * @TODO - This code must be re-usable since it should used in other themes.
 * @returns {boolean}
 */
function onRegisterFormSubmit() {

    showLoader();
    apiUserRegister(objectifyForm($('#register-form')), function(res) {
        if ( res['route'] === 'user.register') {
            if ( res['firebase_custom_login_token'] ) {
                firebaseSignInWithCustomToken(res['firebase_custom_login_token'], function(user) {
                    move(homePage);
                }, function(error) {
                    alertError(error);
                    hideLoader();
                });
            }

        } else {
            alert('Profile update success!');
        }
    }, function(res) {
        showLoader();
        hideLoader();
    });
    return false;
}


