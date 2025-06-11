import 'dart:convert';
import 'dart:developer';
import 'package:http/http.dart' as http;
import 'package:mobile/core/constants/variables.dart';
import 'package:mobile/data/datasource/local/auth_local_datasource.dart';
import 'package:mobile/data/models/merchandise/penukaran_merchandise.dart';

class PenukaranMerchandiseDatasource {
  String? _accessToken;

  Future<String?> _getToken() async {
    if (_accessToken != null) return _accessToken;
    final authData = await AuthLocalDatasource().getUserData();
    _accessToken = authData?.accessToken;
    return _accessToken;
  }

  Future<List<PenukaranMerchandise>> getAllPenukaran() async {
    try {
      final token = await _getToken();
      if (token == null) throw Exception("Token tidak ditemukan");

      final response = await http.get(
        Uri.parse('${Variables.baseUrl}/api/penukaran-merchandise'),
        headers: {
          "Content-Type": "application/json",
          "Authorization": "Bearer $token",
          "Accept": "application/json",
        },
      ).timeout(const Duration(seconds: 10));

      if (response.statusCode == 200) {
        final jsonData = jsonDecode(response.body);
        if (jsonData is List) {
          return jsonData
              .map((item) => PenukaranMerchandise.fromJson(item))
              .toList();
        } else {
          throw Exception("Format response tidak sesuai (harus List)");
        }
      } else {
        throw Exception("Gagal mengambil data penukaran: ${response.statusCode}");
      }
    } catch (e) {
      log("Error getAllPenukaran: $e");
      rethrow;
    }
  }

  Future<PenukaranMerchandise?> getPenukaranById(int id) async {
    try {
      final token = await _getToken();
      if (token == null) throw Exception("Token tidak ditemukan");

      final response = await http.get(
        Uri.parse('${Variables.baseUrl}/api/penukaran-merchandise/$id'),
        headers: {
          "Content-Type": "application/json",
          "Authorization": "Bearer $token",
          "Accept": "application/json",
        },
      ).timeout(const Duration(seconds: 10));

      if (response.statusCode == 200) {
        final jsonData = jsonDecode(response.body);
        return PenukaranMerchandise.fromJson(jsonData);
      } else if (response.statusCode == 404) {
        // Data tidak ditemukan, return null saja
        return null;
      } else {
        throw Exception("Gagal mengambil data penukaran dengan ID: $id");
      }
    } catch (e) {
      log("Error getPenukaranById: $e");
      rethrow;
    }
  }

  Future<bool> createPenukaran(Map<String, dynamic> data) async {
    try {
      final token = await _getToken();
      if (token == null) throw Exception("Token tidak ditemukan");

      final response = await http.post(
        Uri.parse('${Variables.baseUrl}/api/penukaran-merchandise'),
        headers: {
          "Content-Type": "application/json",
          "Authorization": "Bearer $token",
          "Accept": "application/json",
        },
        body: jsonEncode(data),
      ).timeout(const Duration(seconds: 10));

      if (response.statusCode == 201) {
        return true;
      } else {
        log("Gagal createPenukaran: ${response.statusCode} - ${response.body}");
        return false;
      }
    } catch (e) {
      log("Error createPenukaran: $e");
      return false;
    }
  }

  Future<bool> updatePenukaran(int id, Map<String, dynamic> data) async {
    try {
      final token = await _getToken();
      if (token == null) throw Exception("Token tidak ditemukan");

      final response = await http.put(
        Uri.parse('${Variables.baseUrl}/api/penukaran-merchandise/$id'),
        headers: {
          "Content-Type": "application/json",
          "Authorization": "Bearer $token",
          "Accept": "application/json",
        },
        body: jsonEncode(data),
      ).timeout(const Duration(seconds: 10));

      if (response.statusCode == 200) {
        return true;
      } else {
        log("Gagal updatePenukaran: ${response.statusCode} - ${response.body}");
        return false;
      }
    } catch (e) {
      log("Error updatePenukaran: $e");
      return false;
    }
  }

  Future<bool> deletePenukaran(int id) async {
    try {
      final token = await _getToken();
      if (token == null) throw Exception("Token tidak ditemukan");

      final response = await http.delete(
        Uri.parse('${Variables.baseUrl}/api/penukaran-merchandise/$id'),
        headers: {
          "Content-Type": "application/json",
          "Authorization": "Bearer $token",
          "Accept": "application/json",
        },
      ).timeout(const Duration(seconds: 10));

      if (response.statusCode == 204) {
        return true;
      } else {
        log("Gagal deletePenukaran: ${response.statusCode} - ${response.body}");
        return false;
      }
    } catch (e) {
      log("Error deletePenukaran: $e");
      return false;
    }
  }
}
