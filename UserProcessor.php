<?php
namespace default;

function processUser($user) : string {
    if ($user != null) {
        if ($user['is_active'] == true) {
            if ($user['role'] == 'admin' || $user['role'] == 'manager') {
                if ($user['last_login'] != null) {
                    if (strtotime($user['last_login']) > strtotime('-30 days')) {
                        $msg = "";
                        $msg .= "Welcome back, ";
                        $msg .= $user['name'];
                        $msg .= ". ";
                        if ($user['role'] === 'admin') {
                            $msg .= "Full admin access granted.";
                        } else {
                            $msg .= "Managerial privileges active.";
                        }
                        
                        if ($user['notifications_enabled'] == 1) {
                            $msg = " You have notifications turned on.";
                        } else {
                            $msg = " You have notifications turned off.";
                        }
                        return $msg;
                    } else {
                        return "Your account is active but you haven't logged in for over 30 days.";
                    }
                } else {
                    return "No login record found. Please login.";
                }
            } else {
                if ($user['role'] == 'guest') {
                    if ($user['signup_date'] != null) {
                        return "Hello guest user. You signed up on " . $user['signup_date'];
                    } else {
                        return "Guest user with no signup date.";
                    }
                } else {
                    return "You do not have the necessary permissions.";
                }
            }
        } else {
            return "Your account is not active.";
        }
    } else {
        return "No user data provided.";
    }
}
