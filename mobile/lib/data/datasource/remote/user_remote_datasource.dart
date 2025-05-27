import 'dart:convert';
import 'dart:developer';

import 'package:http/http.dart' as http;
import 'package:mobile/core/constants/variables.dart';

class UserRemoteDatasource {
  Future<String?> updateFcmToken(
    String fcmToken,
  ) async {
    final header = {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      // 'Authorization': 'Bearer ${authData.data.token}',
    };

    final url = Uri.parse('${Variables.baseUrl}/api/update-fcm-token/11');
    final response = await http.put(
      url,
      headers: header,
      body: jsonEncode(
        {
          'fcm_token': fcmToken,
        },
      ),
    );

    log("statusCode: ${response.statusCode}");
    log("body: ${response.body}");

    if (response.statusCode == 200) {
      return 'Success Update FCM Token';
    } else {
      return null;
    }
  }

  Future<String?> registerOrg(
    
  ) async {
    final header = {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      // 'Authorization': 'Bearer ${authData.data.token}',
    };

    final url = Uri.parse('${Variables.baseUrl}/api/register-org');
    final response = await http.post(
      url,
      headers: header,
      // body: jsonEncode(
      //   {
      //     'fcm_token': fcmToken,
      //   },
      // ),
    );

    log("statusCode: ${response.statusCode}");
    log("body: ${response.body}");

    if (response.statusCode == 200) {
      return 'Success Update FCM Token';
    } else {
      return null;
    }
  }
}