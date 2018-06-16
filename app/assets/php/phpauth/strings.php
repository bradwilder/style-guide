<?php
$strings = array();

$strings['user_blocked'] = "You are currently locked out of the system.";
$strings['user_deleted'] = "Account has been deleted.";
$strings['user_not_deleted'] = "User has not been deleted.";
$strings['user_not_found'] = "User wasn't found.";

$strings['email_password_incorrect'] = "Email address / password are incorrect.";

$strings['password_short'] = "Password is too short.";
$strings['password_nomatch'] = "Passwords do not match.";
$strings['password_changed'] = "Password changed successfully.";
$strings['password_incorrect'] = "Current password is incorrect.";
$strings['password_must_be_set'] = "Password must be set first.";
$strings['password_must_be_supplied'] = "Password must be supplied.";

$strings['newpassword_nomatch'] = "New passwords do not match.";
$strings['newpassword_match'] = "New password is the same as the old password.";

$strings['email_short'] = "Email address is too short.";
$strings['email_long'] = "Email address is too long.";
$strings['email_invalid'] = "Email address is invalid.";
$strings['email_incorrect'] = "Email address is incorrect.";
$strings['email_banned'] = "This email address is not allowed.";
$strings['email_changed'] = "Email address changed successfully.";

$strings['newemail_match'] = "New email matches previous email.";

$strings['account_inactive'] = "Account has not yet been activated.";
$strings['account_activated'] = "Account activated.";

$strings['logged_in'] = "You are now logged in.";
$strings['logged_out'] = "You are now logged out.";

$strings['email_error'] = "An error occurred sending an email.";
$strings['sms_error'] = "An error occurred sending an sms";

$strings['register_success'] = "Account created. Activation email sent to email.";
$strings['register_success_emailmessage_suppressed'] = "Account created.";
$strings['email_taken'] = "The email address is already in use.";

$strings['resetkey_invalid'] = "Reset key is invalid.";
$strings['resetkey_incorrect'] = "Reset key is incorrect.";
$strings['resetkey_expired'] = "Reset key has expired.";
$strings['password_reset'] = "Password reset successfully.";

$strings['activationkey_invalid'] = "Activation key is invalid.";
$strings['activationkey_incorrect'] = "Activation key is incorrect.";
$strings['activationkey_expired'] = "Activation key has expired.";

$strings['sms_key_invalid'] = "SMS key is invalid.";

$strings['reset_requested'] = "Password reset request sent to email address.";
$strings['reset_exists'] = "A reset request already exists.";

$strings['already_activated'] = "Account is already activated.";
$strings['activation_sent'] = "Activation email has been sent.";
$strings['activation_exists'] = "An activation email has already been sent.";

$strings['email_activation_subject'] = '%s - Activate account';
$strings['email_activation_body'] = 'Hello,<br/><br/> To be able to log in to your account you first need to activate your account by clicking on the following link: <strong><a href="%1$s/%2$s">%1$s/%2$s</a></strong><br/><br/> You then need to use the following activation key: <strong>%3$s</strong><br/><br/> If you did not sign up on %1$s recently then this message was sent in error, please ignore it.';
$strings['email_activation_altbody'] = 'Hello, ' . "\n\n" . 'To be able to log in to your account you first need to activate your account by visiting the following link: %1$s/%2$s' . "\n\n" . 'You then need to use the following activation key: %3$s' . "\n\n" . 'If you did not sign up on %1$s recently then this message was sent in error, please ignore it.';

$strings['email_reset_subject'] = '%s - Password reset request';
$strings['email_reset_body'] = 'Hello,<br/><br/>To reset your password click the following link: <strong><a href="%1$s/%2$s">%1$s/%2$s</a></strong><br/><br/>You then need to use the following password reset key: <strong>%3$s</strong><br/><br/>If you did not request a password reset key on %1$s recently then this message was sent in error, please ignore it.';
$strings['email_reset_altbody'] = 'Hello, ' . "\n\n" . 'To reset your password please visiting the following link: %1$s/%2$s' . "\n\n" . 'You then need to use the following password reset key: %3$s' . "\n\n" . 'If you did not request a password reset key on %1$s recently then this message was sent in error, please ignore it.';

$strings['sms_activation_body'] = '%1$s\nUse this sms key when activating your password:\n%2$s';
$strings['sms_reset_body'] = '%1$s\nUse this sms key when resetting your password:\n%2$s';;

$strings['account_deleted'] = "Account deleted successfully.";
$strings['account_undeleted'] = "Account undeleted successfully.";
$strings['function_disabled'] = "This function has been disabled.";

?>