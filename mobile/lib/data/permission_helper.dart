import 'dart:developer';

import 'package:permission_handler/permission_handler.dart';

class PermessionHelper {
  Future<bool> requestNotificationPermission() async {
    // Periksa apakah izin notifikasi sudah diberikan
    if (await Permission.notification.isGranted) {
      log("Notification permission granted.");
      return true;
    }

    final status = await Permission.notification.request();

    if (status.isGranted) {
      log("Notification permission granted after request.");
    } else if (status.isDenied) {
      log("Notification permission denied.");
    } else if (status.isPermanentlyDenied) {
      log("Notification permission permanently denied. Open app settings.");
      await openAppSettings(); // Membuka pengaturan aplikasi
    }

    return status.isGranted;
  }

  // static Future<String> checkLocationPermission() async {
  //   String locationPermissionStatus;

  //   // Periksa apakah aplikasi memiliki izin lokasi
  //   PermissionStatus locationStatus = await Permission.location.status;

  //   if (locationStatus.isGranted) {
  //     // Periksa apakah izin diberikan untuk "Selalu"
  //     PermissionStatus locationAlwaysStatus =
  //         await Permission.locationAlways.status;

  //     if (locationAlwaysStatus.isGranted) {
  //       locationPermissionStatus = "Always Allowed";
  //     } else {
  //       locationPermissionStatus = "Allowed While In Use";
  //     }
  //   } else if (locationStatus.isDenied) {
  //     locationPermissionStatus = "Denied";
  //   } else if (locationStatus.isPermanentlyDenied) {
  //     locationPermissionStatus = "Permanently Denied (Need to enable manually)";
  //   } else if (locationStatus.isRestricted) {
  //     locationPermissionStatus = "Restricted (Check system settings)";
  //   } else {
  //     locationPermissionStatus = "Unknown";
  //   }

  //   return locationPermissionStatus;
  // }
}