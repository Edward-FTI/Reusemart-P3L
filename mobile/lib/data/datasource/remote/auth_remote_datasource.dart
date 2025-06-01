import 'dart:convert';
import 'dart:developer';

import 'package:mobile/core/constants/variables.dart';
import 'package:mobile/data/models/respons/auth_respon_model.dart';
import 'package:http/http.dart' as http;
class AuthRemoteDatasource {
  Future<AuthResponModel?> login(
      String email, String password) async {
    final response = await http.post(
      Uri.parse('${Variables.baseUrl}/api/login'),
      body: jsonEncode({
        'email': email,
        'password': password,
      }),
      headers: {
        'Content-Type': 'application/json',
        'accept': 'application/json',
      },
    );
    log("Status Code: ${response.statusCode}");
    log("Body: ${response.body}");
    if (response.statusCode == 200) {
      return AuthResponModel.fromJson(jsonDecode(response.body));
    } else {
      return null;
    }
  }
}