// lib/data/models/merchandise/merchandise.dart
import 'package:intl/intl.dart';
import 'package:mobile/core/constants/variables.dart'; // Sesuaikan path ini

class Merchandise {
  final int id;
  final String namaMerchandise;
  final int nilaiPoint;
  final int jumlah; // Perubahan: Menggunakan 'jumlah' sebagai stok
  final String? gambar;

  Merchandise({
    required this.id,
    required this.namaMerchandise,
    required this.nilaiPoint,
    required this.jumlah, // Perubahan: Menggunakan 'jumlah' sebagai stok
    this.gambar,
  });

  factory Merchandise.fromJson(Map<String, dynamic> json) {
    return Merchandise(
      id: json['id'] as int,
      namaMerchandise: json['nama_merchandise'] as String,
      nilaiPoint: json['nilai_point'] as int,
      jumlah: json['jumlah'] as int, // Perubahan: Menggunakan 'jumlah' dari JSON
      gambar: json['gambar'] as String?,
    );
  }

  String get formattedNilaiPoint {
    final format = NumberFormat('#,##0', 'id_ID');
    return format.format(nilaiPoint);
  }

  // Getter untuk mendapatkan URL gambar lengkap
  String get fullGambarUrl {
    if (gambar == null || gambar!.isEmpty) {
      return 'https://via.placeholder.com/150'; // Placeholder jika tidak ada gambar
    }
    return '${Variables.baseUrl}/$gambar'; // Menggunakan base URL dari Variables
  }
}