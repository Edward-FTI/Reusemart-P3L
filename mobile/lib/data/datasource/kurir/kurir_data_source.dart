// import 'dart:convert';
// import 'dart:developer';
// import 'package:mobile/core/constants/variables.dart';
// import 'package:mobile/data/datasource/local/auth_local_datasource.dart';
// import 'package:mobile/data/models/penitip/PenitipModel.dart';
// import 'package:http/http.dart' as http;

// class PenitipDataSource {
//   Future<PenitipModel?> getPenitip() async {
//     final authData = await AuthLocalDatasource().getUserData();
//     final response = await http.get(
//       Uri.parse("${Variables.baseUrl}/api/penitipD"),
//       headers: {
//         "Content-Type": "application/json",
//         'Authorization': 'Bearer ${authData?.accessToken}',
//         'Accept': 'application/json',
//       },
//     );

//     log("status code: ${response.statusCode}");
//     log("body: ${response.body}");

//     if (response.statusCode == 200) {
//       final json = jsonDecode(response.body);

//       if (json is Map<String, dynamic>) {
//         return PenitipModel.fromJson(json);
//       } else {
//         log("Response bukan objek JSON yang valid");
//         return null;
//       }
//     }
//     log("========= Gagal Ambil Data Penitip =========");
//     return null;
//   }
// }
