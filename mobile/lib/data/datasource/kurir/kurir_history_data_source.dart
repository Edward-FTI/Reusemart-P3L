import 'dart:convert';
import 'dart:developer';
import 'package:http/http.dart' as http;
import 'package:mobile/core/constants/variables.dart';
import 'package:mobile/data/datasource/local/auth_local_datasource.dart';
import 'package:mobile/data/models/respons/kurir_history_respon_model.dart';

class KurirHistoryDataSource {
  Future<KurirHistoryResponModel?> getKurirHistory() async {
    final authData = await AuthLocalDatasource().getUserData();

    final response = await http.get(
      Uri.parse("${Variables.baseUrl}/api/pengiriman/selesai"),
      headers: {
        "Content-Type": "application/json",
        "Authorization": "Bearer ${authData?.accessToken}",
        "Accept": "application/json",
      },
    );

    log("Kurir History status code: ${response.statusCode}");
    log("Kurir History body: ${response.body}");

    if (response.statusCode == 200) {
      final json = jsonDecode(response.body);
      if (json is Map<String, dynamic>) {
        return KurirHistoryResponModel.fromJson(json);
      } else {
        log("Response bukan objek JSON yang valid");
        return null;
      }
    }

    log("========= Gagal Ambil Data History Pengiriman =========");
    return null;
  }
}
