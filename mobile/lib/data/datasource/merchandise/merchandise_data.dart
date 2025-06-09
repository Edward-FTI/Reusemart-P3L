// services/merchandise_service.dart
import 'dart:convert';
import 'dart:developer';
import 'package:http/http.dart' as http;
import 'package:mobile/data/models/merchandise/merchandise.dart';
import 'package:mobile/core/constants/variables.dart';

class MerchandiseService {
  Future<List<Merchandise>> getAllMerchandise() async {
    final response = await http.get(Uri.parse('${Variables.baseUrl}/api/merchandise'));

    if (response.statusCode == 200) {
      final List<dynamic> data = jsonDecode(response.body);

      return data.map((item) => Merchandise.fromJson(item)).toList();
    } else {
      throw Exception('Gagal mengambil data merchandise');
    }
  }
}
