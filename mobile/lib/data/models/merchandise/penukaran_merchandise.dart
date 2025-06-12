// lib/data/models/merchandise/penukaran_merchandise.dart
import 'package:mobile/data/models/merchandise/merchandise.dart';
import 'package:mobile/data/models/pembeli/ModelPembeli.dart';
import 'package:mobile/data/models/pembeli/ModelPembeli.dart'; // <<<--- Import Pembeli yang konsisten
import 'package:intl/intl.dart';

class PenukaranMerchandise {
  final int id;
  final int id_pembeli;
  final int id_merchandise;
  final int? id_pegawai; // ID CS yang mencatat pengambilan
  final DateTime tanggalPenukaran;
  final String status;
  final int jumlah;

  // Relasi
  final PembeliModel? pembeli; // Menggunakan Pembeli
  final Merchandise? merchandise;

  PenukaranMerchandise({
    required this.id,
    required this.id_pembeli,
    required this.id_merchandise,
    this.id_pegawai,
    required this.tanggalPenukaran,
    required this.status,
    required this.jumlah,
    this.pembeli,
    this.merchandise,
  });

  factory PenukaranMerchandise.fromJson(Map<String, dynamic> json) {
    return PenukaranMerchandise(
      id: json['id'] as int,
      id_pembeli: json['id_pembeli'] as int,
      id_merchandise: json['id_merchandise'] as int,
      id_pegawai: json['id_pegawai'] != null && json['id_pegawai'] != 0
          ? json['id_pegawai'] as int
          : null,
      tanggalPenukaran: DateTime.parse(json['tanggal_penukaran'] as String),
      status: json['status'] as String,
      jumlah: json['jumlah'] as int,
      pembeli: json['pembeli'] != null
          ? PembeliModel.fromJson(json['pembeli']) // Menggunakan Pembeli.fromJson
          : null,
      merchandise: json['merchandise'] != null
          ? Merchandise.fromJson(json['merchandise'])
          : null,
    );
  }

  String get formattedTanggalPenukaran {
    return DateFormat('dd MMMM yyyy, HH:mm').format(tanggalPenukaran);
  }

  // Getter yang digunakan di CS web, bisa dipertahankan atau dihapus jika tidak ada CS web
  bool get isPegawaiKosong => id_pegawai == null || id_pegawai == 0;
}