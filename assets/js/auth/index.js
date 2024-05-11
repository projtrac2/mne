$(document).ready(function () {
    $('#login-btn').on('click', (e) => {
        e.preventDefault();
        if (!$('#email').val()) {
            $('#email').next().text('field required');
            return;
        } else {
            $('#email').next().text('');
        }
        if (!$('#password').val()) {
            $('#password').next().text('field required');
            return;
        } else {
            $('#password').next().text('');
        }
        $('#loginusers').submit();
    });

    $('#forgot-btn').on('click', (e) => {
        e.preventDefault();
        if (!$('#email').val()) {
            $('#email').next().text('field required');
            return;
        } else {
            $('#email').next().text('');
        }
        $('#loginusers').submit();
    })

    $('#otp-btn').on('click', (e) => {
        e.preventDefault();
        if (!$('#otp_code').val()) {
            $('#otp_code').next().text('field required');
            return;
        } else {
            $('#otp_code').next().text('');
        }
        $('#loginusers').submit();
    });

    $('#resend-btn').on('click', (e) => {
        e.preventDefault();
        $('#resend-form').submit();
    });

    $('#reset-btn').on('click', (e) => {
        e.preventDefault();

        if (!$('#email').val()) {
            $('#email').next().text('field required');
            return;
        } else {
            $('#email').next().text('');
        }

        if (!$('#password').val()) {
            $('#password').next().text('field required');
            return;
        } else {
            $('#password').next().text('');
        }

        if (!$('#confirm_password').val()) {
            $('#confirm_password').next().text('field required');
            return;
        } else {
            $('#confirm_password').next().text('');
        }

        $('#loginusers').submit();
    });

    $('#setpass-btn').on('click', (e) => {
        e.preventDefault();
        if (!$('#password').val()) {
            $('#password').next().text('field required');
            return;
        } else {
            $('#password').next().text('');
        }

        if (!$('#confirm_password').val()) {
            $('#confirm_password').next().text('field required');
            return;
        } else {
            $('#confirm_password').next().text('');
        }

        $('#loginusers').submit();
    });
});