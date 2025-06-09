import 'dart:convert';
import 'dart:developer';
import 'package:http/http.dart' as http;
import 'package:mobile/core/constants/variables.dart';
import 'package:mobile/data/datasource/local/auth_local_datasource.dart';
import 'package:mobile/data/models/merchandise/penukaran_merchandise.dart';

class PenukaranMerchandiseDatasource {
  /// Ambil semua data penukaran merchandise
  Future<List<PenukaranMerchandise>> getAllPenukaran() async {
    final authData = await AuthLocalDatasource().getUserData();

    final response = await http.get(
      Uri.parse('${Variables.baseUrl}/api/penukaran-merchandise'),
      headers: {
        "Content-Type": "application/json",
        "Authorization": "Bearer ${authData?.accessToken}",
        "Accept": "application/json",
      },
    );

    log("Status Code: ${response.statusCode}");
    log("Response Body: ${response.body}");

    if (response.statusCode == 200) {
      final jsonData = jsonDecode(response.body);

      if (jsonData is List) {
        return jsonData
            .map((item) => PenukaranMerchandise.fromJson(item))
            .toList();
      } else {
        log("Format response tidak sesuai (harus List)");
        return [];
      }
    } else {
      log("Gagal mengambil data penukaran merchandise");
      return [];
    }
  }

  /// Ambil satu data penukaran berdasarkan ID
  Future<PenukaranMerchandise?> getPenukaranById(int id) async {
    final authData = await AuthLocalDatasource().getUserData();

    final response = await http.get(
      Uri.parse('${Variables.baseUrl}/api/penukaran-merchandise/$id'),
      headers: {
        "Content-Type": "application/json",
        "Authorization": "Bearer ${authData?.accessToken}",
        "Accept": "application/json",
      },
    );

    log("Status Code: ${response.statusCode}");
    log("Response Body: ${response.body}");

    if (response.statusCode == 200) {
      final jsonData = jsonDecode(response.body);
      return PenukaranMerchandise.fromJson(jsonData);
    } else {
      log("Gagal mengambil data penukaran dengan ID: $id");
      return null;
    }
  }

  /// Buat penukaran baru
  Future<bool> createPenukaran(Map<String, dynamic> data) async {
    final authData = await AuthLocalDatasource().getUserData();

    final response = await http.post(
      Uri.parse('${Variables.baseUrl}/api/penukaran-merchandise'),
      headers: {
        "Content-Type": "application/json",
        "Authorization": "Bearer ${authData?.accessToken}",
        "Accept": "application/json",
      },
      body: jsonEncode(data),
    );

    log("Status Code: ${response.statusCode}");
    log("Response Body: ${response.body}");

    return response.statusCode == 201;
  }

  /// Ubah status atau data penukaran
  Future<bool> updatePenukaran(int id, Map<String, dynamic> data) async {
    final authData = await AuthLocalDatasource().getUserData();

    final response = await http.put(
      Uri.parse('${Variables.baseUrl}/api/penukaran-merchandise/$id'),
      headers: {
        "Content-Type": "application/json",
        "Authorization": "Bearer ${authData?.accessToken}",
        "Accept": "application/json",
      },
      body: jsonEncode(data),
    );

    log("Status Code: ${response.statusCode}");
    log("Response Body: ${response.body}");

    return response.statusCode == 200;
  }

  /// Hapus data penukaran
  Future<bool> deletePenukaran(int id) async {
    final authData = await AuthLocalDatasource().getUserData();

    final response = await http.delete(
      Uri.parse('${Variables.baseUrl}/api/penukaran-merchandise/$id'),
      headers: {
        "Content-Type": "application/json",
        "Authorization": "Bearer ${authData?.accessToken}",
        "Accept": "application/json",
      },
    );

    log("Status Code: ${response.statusCode}");
    return response.statusCode == 204;
  }
}
