// lib/services/api_service.dart
import 'dart:convert';
import 'dart:developer';
import 'package:http/http.dart' as http;
import 'package:mobile/data/models/barang/barang.dart'; // <<<--- IMPORT BARU
import 'package:mobile/core/constants/variables.dart'; // <<<--- IMPORT BARU

class ApiService {
  Future<List<Barang>> fetchAllBarang() async {
    final response = await http.get(Uri.parse('${Variables.baseUrl}/api/barang'));

    log("Status code: ${response.statusCode}");
    log("Body: ${response.body}");

    if (response.statusCode == 200) {
      final Map<String, dynamic> responseData = json.decode(response.body);
      
      if (responseData['data'] is List) {
        List<dynamic> barangJson = responseData['data'];
        return barangJson.map((json) => Barang.fromJson(json)).toList();
      } else {
        log("API response 'data' is not a List or is null: ${responseData['data']}");
        throw Exception("Invalid data format from API.");
      }
    } else {
      log("Failed to load barang: ${response.statusCode}");
      throw Exception('Failed to load barang: ${response.statusCode}');
    }
  }
}