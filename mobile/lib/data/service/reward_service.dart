import 'dart:convert';
import 'dart:developer';
import 'package:http/http.dart' as http;
import 'package:mobile/core/constants/variables.dart';
import 'package:mobile/data/models/merchandise/merchandise.dart';
import 'package:mobile/data/models/merchandise/penukaran_merchandise.dart';
import 'package:mobile/data/models/pembeli/ModelPembeli.dart';
import 'package:mobile/data/datasource/pembeli/pembeli_data_source.dart';
import 'package:mobile/data/datasource/local/auth_local_datasource.dart';

class RewardService {
  final String _baseUrl = Variables.baseUrl;
  final PembeliDataSource _pembeliDataSource = PembeliDataSource();
  final AuthLocalDatasource _authLocalDatasource = AuthLocalDatasource();

  /// Mendapatkan token otentikasi dari penyimpanan lokal
  Future<String?> _getToken() async {
    final authData = await _authLocalDatasource.getUserData();
    final token = authData?.accessToken;

    log("Token diambil dari getUserData(): $token");

    return token;
  }

  /// Header standar untuk request ke backend
  Map<String, String> _getHeaders(String? token) {
    return {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      if (token != null) 'Authorization': 'Bearer $token',
    };
  }

  /// FUNGSI 120: Melihat katalog merchandise
  Future<List<Merchandise>> fetchMerchandiseCatalog() async {
    final response = await http.get(Uri.parse('$_baseUrl/api/merchandise'));

    log("Status code fetchMerchandiseCatalog: ${response.statusCode}");
    log("Body fetchMerchandiseCatalog: ${response.body}");

    if (response.statusCode == 200) {
      final Map<String, dynamic> responseData = json.decode(response.body);
      if (responseData['data'] is List) {
        return (responseData['data'] as List)
            .map((json) => Merchandise.fromJson(json))
            .toList();
      }
      throw Exception('Invalid data format: ${responseData['data']}');
    }
    throw Exception('Failed to load merchandise catalog: ${response.statusCode}');
  }

  /// Mendapatkan profil pembeli yang sedang login (untuk poin)
  Future<PembeliModel> fetchCurrentPembeliProfile() async {
    final pembeli = await _pembeliDataSource.getPembeli();
    if (pembeli != null) {
      return pembeli;
    } else {
      throw Exception('Gagal mengambil data pembeli.');
    }
  }

  /// Melakukan Klaim merchandise
  Future<List<PenukaranMerchandise>> claimMerchandise(List<Map<String, dynamic>> itemsToClaim) async {
    final token = await _getToken();
    if (token == null) throw Exception('No authentication token found.');

    final requestBody = json.encode({'items': itemsToClaim});

    log("Token yang dikirim: $token");
    log("Items to claim (encoded): $requestBody");

    final response = await http.post(
      Uri.parse('$_baseUrl/api/penukaran-merchandise-login'),
      headers: _getHeaders(token),
      body: requestBody,
    );

    log("Status code claimMerchandise: ${response.statusCode}");
    log("Body claimMerchandise: ${response.body}");

    if (response.statusCode == 200 || response.statusCode == 201) {
      final Map<String, dynamic> responseData = json.decode(response.body);
      if (responseData['success'] == true && responseData['data'] is List) {
        return (responseData['data'] as List)
            .map((json) => PenukaranMerchandise.fromJson(json))
            .toList();
      }
      throw Exception(responseData['message'] ?? 'Invalid response from claim API.');
    } else {
      final errorData = json.decode(response.body);
      throw Exception('Failed to claim merchandise: ${errorData['message'] ?? 'Unknown error'}');
    }
  }

  /// Melihat riwayat klaim pembeli
  Future<List<PenukaranMerchandise>> fetchMyClaims() async {
    final token = await _getToken();
    if (token == null) throw Exception('No authentication token found.');

    final response = await http.get(
      Uri.parse('$_baseUrl/api/penukaran-merchandise-login'),
      headers: _getHeaders(token),
    );

    log("Status code fetchMyClaims: ${response.statusCode}");
    log("Body fetchMyClaims: ${response.body}");

    if (response.statusCode == 200) {
      final Map<String, dynamic> responseData = json.decode(response.body);
      if (responseData['success'] == true && responseData['data'] is List) {
        return (responseData['data'] as List)
            .map((json) => PenukaranMerchandise.fromJson(json))
            .toList();
      }
      throw Exception(responseData['message'] ?? 'Invalid data format for my claims.');
    }
    throw Exception('Failed to load my claims: ${response.statusCode}');
  }
}
