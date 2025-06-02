import 'dart:developer';

import 'package:firebase_core/firebase_core.dart';
import 'package:firebase_messaging/firebase_messaging.dart';
import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
// import 'package:flutter/material.dart';
// import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_local_notifications/flutter_local_notifications.dart';
import 'package:mobile/data/datasource/local/auth_local_datasource.dart';
import 'package:mobile/data/datasource/remote/user_remote_datasource.dart';

// import '../../presentation/admin/pages/chat_menu_page.dart';
// import '../../presentation/user/bloc/bloc.dart';
// import '../../presentation/user/bloc/main_user_nav/main_user_nav_bloc.dart';
// import 'auth_local_datasource.dart';
// import 'auth_remote_datasource.dart';
// import 'device_info_datasource.dart';

class FirebaseMessangingRemoteDatasource {
  final FirebaseMessaging _firebaseMessaging = FirebaseMessaging.instance;
  final flutterLocalNotificationsPlugin = FlutterLocalNotificationsPlugin();

  Future<void> initialize() async {
    // _mainNavBloc = context.read<MainUserNavBloc>();
    await _firebaseMessaging.requestPermission(
      alert: true,
      badge: true,
      sound: true,
    );

    const initializationSettingsAndroid =
        AndroidInitializationSettings('@mipmap/ic_launcher');
    final initializationSettingsIOS = DarwinInitializationSettings(
        requestAlertPermission: true,
        requestBadgePermission: true,
        requestSoundPermission: true,
        onDidReceiveLocalNotification:
            (int id, String? title, String? body, String? payload) async {
          showNotification(id: id, title: title, body: body, payLoad: payload);
        });

    final initializationSettings = InitializationSettings(
      android: initializationSettingsAndroid,
      iOS: initializationSettingsIOS,
    );
    await flutterLocalNotificationsPlugin.initialize(
      initializationSettings,
      onDidReceiveNotificationResponse:
          (NotificationResponse notificationResponse) async {
        // handleNotificationTap(context, notificationResponse.payload);
      },
    );

    const AndroidNotificationChannel channel = AndroidNotificationChannel(
      'com.package.namepm',
      'app',
      description: 'Channel for JKT48PM notifications',
      importance: Importance.max,
      // sound: RawResourceAndroidNotificationSound(
      //     'notification'),
      playSound: true,
    );

    await flutterLocalNotificationsPlugin
        .resolvePlatformSpecificImplementation<
            AndroidFlutterLocalNotificationsPlugin>()
        ?.createNotificationChannel(channel);

    // await deviceInfoDatasource.initPlatformState();

    final fcmToken = await _firebaseMessaging.getToken();
    log("FCM Token: $fcmToken");
final authData = await AuthLocalDatasource().getUserData();
    if (authData != null) {
      final userId = authData.user?.id;
      if (userId != null) {
        await UserRemoteDatasource().updateFcmToken(
          fcmToken ?? '',
        );
      }
    }
    // final modelDevice = deviceInfoDatasource.getAllDeviceInfo();
    //String deviceInfoString = '';
    // modelDevice.forEach((key, value) {
    //   deviceInfoString += '$key: $value\n';
    // });


    debugPrint('FCM Token: $fcmToken');
    // debugPrint('Model Device: $deviceInfoString');

    // if (await AuthLocalDatasource().getAccessToken() != null) {
    //   AuthRemoteDatasource().updateFcmToken(fcmToken ?? '', deviceInfoString);
    // }

    FirebaseMessaging.instance.getInitialMessage();
    FirebaseMessaging.onMessage.listen((message) {
      debugPrint('Message received: ${message.notification?.title}, ${message.notification?.body}');
    });

    FirebaseMessaging.onMessage.listen(firebaseBackgroundHandler);
    // FirebaseMessaging.onBackgroundMessage(_firebaseMessagingBackgroundHandler);
    FirebaseMessaging.onMessageOpenedApp.listen(firebaseBackgroundHandler);
  }

  Future showNotification({
    int id = 0,
    String? title,
    String? body,
    String? payLoad,
    String? imageUrl,
  }) async {
    const androidPlatformChannelSpecifics = AndroidNotificationDetails(
      'com.jkt48pm.superpm', // Channel ID
      'app', // Channel name
      importance: Importance.max,
      priority: Priority.high,
      // sound: RawResourceAndroidNotificationSound(
      //     'notification_sound'), // Android custom sound
      playSound: true,
    );

    const iOSPlatformChannelSpecifics = DarwinNotificationDetails(
      //sound: 'notification_sound.caf',
      sound: 'default',
      presentSound: true, presentBadge: true,
      // iOS custom sound
    );

    const notificationDetails = NotificationDetails(
      android: androidPlatformChannelSpecifics,
      iOS: iOSPlatformChannelSpecifics,
    );

    return flutterLocalNotificationsPlugin.show(
      id,
      title,
      body,
      payload: payLoad,
      notificationDetails,
    );
  }

  @pragma('vm:entry-point')
  Future<void> _firebaseMessagingBackgroundHandler(
      RemoteMessage message) async {
    await Firebase.initializeApp();

    //FirebaseMessangingRemoteDatasource().firebaseBackgroundHandler(message);
    firebaseBackgroundHandler(message);
  }

  Future<void> firebaseBackgroundHandler(RemoteMessage message) async {
    // add URL image from message
    //final imageUrl = message.data['imageUrl'];

    await showNotification(
      id: message.messageId?.hashCode ?? 0,
      title: message.notification?.title,
      body: message.notification?.body,
      payLoad: message.messageId,

      //imageUrl: imageUrl,
    );
    debugPrint('Notification Payload: ${message.messageId}');
    debugPrint(
        'Received notification: ${message.notification?.title}, ${message.notification?.body}');
  }

  void handleNotificationTap(BuildContext context, String? payload) async {
    
  }
}