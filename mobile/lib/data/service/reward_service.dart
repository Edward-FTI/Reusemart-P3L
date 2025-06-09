// lib/services/reward_service.dart
import 'dart:convert';
import 'dart:developer';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import 'package:mobile/core/constants/variables.dart';
import 'package:mobile/data/models/merchandise/merchandise.dart';
import 'package:mobile/data/models/merchandise/penukaran_merchandise.dart';
import 'package:mobile/data/models/pembeli/ModelPembeli.dart'; // Import model Pembeli

class RewardService {
  final String _baseUrl = Variables.baseUrl;

  Future<String?> _getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('token');
  }

  Map<String, String> _getHeaders(String? token) {
    return {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      if (token != null) 'Authorization': 'Bearer $token',
    };
  }

  // --- Pembeli-side APIs ---

  // FUNGSI 120: Melihat katalog merchandise
  Future<List<Merchandise>> fetchMerchandiseCatalog() async {
    final response = await http.get(Uri.parse('$_baseUrl/api/merchandise'));
    log("Status code fetchMerchandiseCatalog: ${response.statusCode}");
    log("Body fetchMerchandiseCatalog: ${response.body}");

    if (response.statusCode == 200) {
      final Map<String, dynamic> responseData = json.decode(response.body);
      if (responseData['data'] is List) {
        return (responseData['data'] as List).map((json) => Merchandise.fromJson(json)).toList();
      }
      throw Exception('Invalid data format: ${responseData['data']}');
    }
    throw Exception('Failed to load merchandise catalog: ${response.statusCode}');
  }

  // FUNGSI API BARU: Mendapatkan profil pembeli yang sedang login (untuk poin)
  Future<PembeliModel> fetchCurrentPembeliProfile() async {
    final token = await _getToken();
    if (token == null) throw Exception('No authentication token found.');

    final response = await http.get(
      Uri.parse('$_baseUrl/api/user'), // Endpoint default Sanctum untuk user yang login
      headers: _getHeaders(token),
    );

    log("Status code fetchCurrentPembeliProfile: ${response.statusCode}");
    log("Body fetchCurrentPembeliProfile: ${response.body}");

    if (response.statusCode == 200) {
      final Map<String, dynamic> responseData = json.decode(response.body);
      // Asumsi API /api/user mengembalikan langsung data Pembeli (atau User yang bisa diparse ke Pembeli)
      return PembeliModel.fromJson(responseData); 
    }
    throw Exception('Failed to load buyer profile: ${response.statusCode}');
  }

  // FUNGSI 121: Melakukan Klaim merchandise
  // Mengirim array item seperti yang diharapkan controller Laravel Anda
  Future<List<PenukaranMerchandise>> claimMerchandise(List<Map<String, dynamic>> itemsToClaim) async {
    final token = await _getToken();
    if (token == null) throw Exception('No authentication token found.');

    final response = await http.post(
      Uri.parse('$_baseUrl/api/penukaran-merchandise-login'), // Menggunakan rute yang Anda tentukan
      headers: _getHeaders(token),
      body: json.encode({'items': itemsToClaim}), // Mengirim array items
    );

    log("Status code claimMerchandise: ${response.statusCode}");
    log("Body claimMerchandise: ${response.body}");

    if (response.statusCode == 200) { // Sukses biasanya 200 atau 201
      final Map<String, dynamic> responseData = json.decode(response.body);
      if (responseData['success'] == true && responseData['data'] is List) {
        // Jika backend mengembalikan array penukaran yang dibuat
        return (responseData['data'] as List).map((json) => PenukaranMerchandise.fromJson(json)).toList();
      }
      throw Exception(responseData['message'] ?? 'Invalid response from claim API.');
    } else {
      final errorData = json.decode(response.body);
      throw Exception('Failed to claim merchandise: ${errorData['message'] ?? 'Unknown error'}');
    }
  }

  // Metode untuk melihat riwayat klaim pembeli
  Future<List<PenukaranMerchandise>> fetchMyClaims() async {
    final token = await _getToken();
    if (token == null) throw Exception('No authentication token found.');

    final response = await http.get(
      Uri.parse('$_baseUrl/api/penukaran-merchandise-login'), // Menggunakan rute yang Anda tentukan
      headers: _getHeaders(token),
    );

    log("Status code fetchMyClaims: ${response.statusCode}");
    log("Body fetchMyClaims: ${response.body}");

    if (response.statusCode == 200) {
      final Map<String, dynamic> responseData = json.decode(response.body);
      if (responseData['success'] == true && responseData['data'] is List) {
        return (responseData['data'] as List).map((json) => PenukaranMerchandise.fromJson(json)).toList();
      }
      throw Exception(responseData['message'] ?? 'Invalid data format for my claims.');
    }
    throw Exception('Failed to load my claims: ${response.statusCode}');
  }

  // Tambahan: Metode untuk CS mengupdate status klaim (diperlukan di backend, tapi bisa dipanggil dari Flutter Admin UI)
  Future<PenukaranMerchandise> updateClaimStatus(int claimId, String newStatus, {int? idPegawai}) async {
    final token = await _getToken(); // Asumsi CS/Admin juga menggunakan token
    if (token == null) throw Exception('No authentication token found.');

    final response = await http.put(
      Uri.parse('$_baseUrl/api/penukaran-merchandise/$claimId/status'), // Sesuaikan endpoint
      headers: _getHeaders(token),
      body: json.encode({
        'status': newStatus,
        if (idPegawai != null) 'id_pegawai': idPegawai,
      }),
    );

    if (response.statusCode == 200) {
      return PenukaranMerchandise.fromJson(json.decode(response.body)['data']);
    } else {
      final errorData = json.decode(response.body);
      throw Exception('Failed to update claim status: ${errorData['message'] ?? 'Unknown error'}');
    }
  }
}