import 'dart:convert';
import 'dart:developer';
import 'package:http/http.dart' as http;

import 'package:mobile/core/constants/variables.dart';
import 'package:mobile/data/datasource/local/auth_local_datasource.dart';
import 'package:mobile/data/models/hunter/HistoryHunterModel.dart';

class HunterHistoryDataSource {
static Future<List<Datum>?> getHistoryHunteran() async {
  try {
    final authData = await AuthLocalDatasource().getUserData();

    final headers = {
      "Content-Type": "application/json",
      "Accept": "application/json",
      if (authData?.accessToken != null)
        "Authorization": "Bearer ${authData!.accessToken}"
    };

    final response = await http.get(
      Uri.parse("${Variables.baseUrl}/api/hunter-history"),
      headers: headers,
    );

    if (response.statusCode == 200) {
      final json = jsonDecode(response.body);
      final List<dynamic> data = json['data'];

      return data.map((item) => Datum.fromJson(item)).toList();
    } else {
      log("Gagal memuat history hunter: ${response.statusCode}");
      return null;
    }
  } catch (e) {
    log("Error saat getHistoryHunteran: $e");
    return null;
  }
}


  static Future<http.Response?> getBarangHunter() async {
    try {
      final authData = await AuthLocalDatasource().getUserData();

      final headers = {
        "Content-Type": "application/json",
        "Accept": "application/json",
        if (authData?.accessToken != null)
          "Authorization": "Bearer ${authData!.accessToken}"
      };

      final response = await http.get(
        Uri.parse("${Variables.baseUrl}/api/barangHunter"),
        headers: headers,
      );

      if (response.statusCode == 200) {
        return response;
      } else {
        log("Gagal memuat barang hunter: ${response.statusCode}");
        return null;
      }
    } catch (e) {
      log("Error saat getBarangHunter: $e");
      return null;
    }
  }
}
