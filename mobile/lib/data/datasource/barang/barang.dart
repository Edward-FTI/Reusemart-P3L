import 'dart:convert';
import 'dart:developer';
import 'package:mobile/core/constants/variables.dart';
import 'package:mobile/data/models/pembeli/ModelPembeli.dart';
import 'package:http/http.dart' as http;

class PembeliDataSource {
  Future<PembeliModel?> getPembeli() async {
    final response = await http.get(
      Uri.parse("${Variables.baseUrl}/api/barang"),
      headers: {
        "Content-Type": "application/json",
        'Accept': 'application/json',
      },
    );

    log("status code: ${response.statusCode}");
    log("body: ${response.body}");

    if (response.statusCode == 200) {
      final json = jsonDecode(response.body);

      if (json is Map<String, dynamic>) {
        return PembeliModel.fromJson(json);
      } else {
        log("Response bukan objek JSON yang valid");
        return null;
      }
    }
    log("========= Gagal Ambil Data Pembeli =========");
    return null;
  }
}
