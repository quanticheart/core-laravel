<?php

use Illuminate\Support\Facades\Route;

const folderController = __DIR__ . '\..\Controllers\\';
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

const pushController = folderController . 'Push\PushController@';
const notificationController = folderController . 'Notification\NotificationController@';

/**
 * User's Routes
 *
 */
Route::group(["prefix" => "user"], function () {
    Route::group(["prefix" => "notify"], function () {
        /* push */
//        Route::post('/push/all', pushController . 'sendNotificationToAllUser');
//        Route::post('/push', pushController . 'sendNotificationToUser');
        Route::post('/push/save-token', pushController . 'saveToken');

        /* notification */
        Route::post('/send', notificationController . 'newNotification');
        Route::post('/send/email', notificationController . 'newNotificationWithEmail');
        Route::post('/send/sms', notificationController . 'newNotificationSms');

        Route::post('/list', notificationController . 'allNotificationList');
        Route::post('/list/unread', notificationController . 'unreadNotificationList');
        Route::post('/list/read', notificationController . 'readNotificationList');

        Route::post('/read', notificationController . 'readNotificationByID');
    });
});

