<?php

namespace App\Constants;

class ApiResponseMessage
{
    const SUCCESS = "Success";
    const NOT_FOUND = "No Record Found yet, Please try after sometime.";
    const LOGGED_IN_SUCCESS_MESSAGE = "Welcome back! You are now signed in successfully! 😊";
    const SIGNUP_SUCCESS_MESSAGE = "Congratulations! You have successfully signed up! 🎉";
    const NEW_USER_EXISTS = "This Is new user!";
    const OLD_USER_EXISTS = "This Is Old user!";
    const LOGIN_FAILED_MESSAGE = 'Login failed. Invalid credentials.';
    const PROFILE_UPDATED_MESSAGE = "Great job! Your profile has been updated successfully! 🚀";

    const FORGOT_PASSWORD_SUCCESS_MESSAGE = 'FORGOT_PASSWORD_SUCCESS_MESSAGE' ;
    const RESET_PASSWORD_LINK = 'Reset Password link send on your email' ;
    const EMAIL_VERIFY = 'Your e-mail is verified. You can now login.' ;
    const EMAIL_ALREADY_VERIFIED = 'Your e-mail is already verified. You can now login.' ;

    const UNAUTHORISED_MESSAGE = "Oops! Incorrect credentials or your account has been temporarily blocked by the server admin. Please double-check your information or contact support for assistance. 🚫";
    const DATA_FOUND_MESSAGE = "Data Found.";
    const ACCOUNT_LOCKED_MESSAGE = "Uh-oh! Your account has been temporarily locked for security reasons. Don't worry, contact our support team, and we'll get it sorted for you. 🔒";
    const PASSWORD_RESET_SUCCESS_MESSAGE = "Success! Your password has been reset. You're all set with a new password! 🎊";
    const FILE_UPLOAD_SUCCESS_MESSAGE = "Awesome! Your file has been successfully uploaded. 📁";
    const ORDER_PLACED_MESSAGE = "Congratulations! Your order has been placed successfully. We can't wait to deliver your goodies! 🛍️";
    const FORM_SUBMISSION_SUCCESS_MESSAGE = "Thanks a bunch! Your form has been submitted successfully. We'll get back to you soon! 📝";
    const PASSWORD_CHANGED_MESSAGE = "Success! Your password has been changed 🔒";
    const ITEM_ADDED_TO_CART_MESSAGE = "Great choice! The item has been added to your cart. Happy shopping! 🛒";
    const PROFILE_DELETED_MESSAGE = "Goodbye! Your profile has been deleted successfully. We'll miss you! 😢";

// stripe message
    const CUSTOMER_CREATED_SUCCESS_MESSAGE ="Congratulations!Customer created successfully! 🎉.";
    const USER_NOT_FOUND_GIVEN_EMAIL ="User not found with the provided email 😢!";
    const USER_ALL_READY_EXISTS_ON_STRIPE_GIVEN_EMAIL ="Customer with the provided email already exists in Stripe";
    const CUSTOMER_FETCHED_SUCCESS_MESSAGE ="Customer fetched successfully😊!";
    const PAYMENT_METHODS_FETCHED_SUCCESS_MESSAGE ="Payment method fetched successfully😊!";
    const STRIPE_CARD_ADDED_SUCCESS_MESSAGE ="Stripe card added successfully😊!";
    const CUSTOMER_CARD_ADDED_SUCCESS_MESSAGE ="Customer card added successfully😊!";
    const PAYMENT_SUCCESS_MESSAGE ="Payment successfull😊!";
    const PRODUCT_AND_PRICE_CREATED_SUCCESS_MESSAGE ="Product and its price created successfully on stripe😊!";
    const CUSTOMER_SUBSCRIPTION_CREATED_SUCCESS_MESSAGE ="Subscription created successfully!";
    const CUSTOMER_SUBSCRIPTION_PAUSED_SUCCESS_MESSAGE ="You subscription has been paused!";
    const CUSTOMER_SUBSCRIPTION_RESUMED_SUCCESS_MESSAGE ="You subscription has been resume!";
    const CUSTOMER_SUBSCRIPTION_UPDATED_MESSAGE ="Subscription updated successfully!";
    const CUSTOMER_SUBSCRIPTION_CANCEL_MESSAGE ="Subscription successfully canceled!";
    const SUBSCRIPTION_PLAN_SUCCESS_MESSAGE ="Subscription plan fetched successfully😊!";








}
