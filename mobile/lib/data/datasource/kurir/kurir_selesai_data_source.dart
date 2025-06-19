import 'dart:convert';
import 'dart:developer';
import 'package:http/http.dart' as http;
import 'package:mobile/core/constants/variables.dart';
import 'package:mobile/data/datasource/local/auth_local_datasource.dart';
import 'package:mobile/data/models/respons/kurir_selesai_respon_model.dart';

class KurirSelesaiDataSource {
  Future<KurirSelesaiResponModel?> selesaikanPengiriman(int id) async {
    final authData = await AuthLocalDatasource().getUserData();

    final response = await http.put(
      Uri.parse("${Variables.baseUrl}/api/pengiriman/$id/selesai"),
      headers: {
        "Content-Type": "application/json",
        "Authorization": "Bearer ${authData?.accessToken}",
        "Accept": "application/json",
      },
    );

    log("Selesai Pengiriman status code: ${response.statusCode}");
    log("Selesai Pengiriman body: ${response.body}");

    if (response.statusCode == 200) {
      final json = jsonDecode(response.body);
      if (json is Map<String, dynamic>) {
        return KurirSelesaiResponModel.fromJson(json);
      } else {
        log("Response bukan objek JSON yang valid");
        return null;
      }
    }

    log("========= Gagal Menyelesaikan Pengiriman =========");
    return null;
  }
}
