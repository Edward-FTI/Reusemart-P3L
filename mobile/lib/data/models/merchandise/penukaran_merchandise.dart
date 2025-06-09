// lib/data/models/merchandise/penukaran_merchandise.dart
import 'package:mobile/data/models/merchandise/merchandise.dart';
import 'package:mobile/data/models/pembeli/ModelPembeli.dart';
import 'package:intl/intl.dart';

class PenukaranMerchandise {
  final int id;
  final int idPembeli;
  final int idMerchandise;
  final int? idPegawai; // ID CS yang mencatat pengambilan
  final DateTime tanggalPenukaran;
  final String status;
  final int jumlah;

  // Relasi
  final PembeliModel? pembeli;
  final Merchandise? merchandise;

  PenukaranMerchandise({
    required this.id,
    required this.idPembeli,
    required this.idMerchandise,
    this.idPegawai,
    required this.tanggalPenukaran,
    required this.status,
    required this.jumlah,
    this.pembeli,
    this.merchandise,
  });

  factory PenukaranMerchandise.fromJson(Map<String, dynamic> json) {
    return PenukaranMerchandise(
      id: json['id'] as int,
      idPembeli: json['id_pembeli'] as int,
      idMerchandise: json['id_merchandise'] as int,
      idPegawai: json['id_pegawai'] != null && json['id_pegawai'] != 0
          ? json['id_pegawai'] as int
          : null,
      tanggalPenukaran: DateTime.parse(json['tanggal_penukaran'] as String),
      status: json['status'] as String,
      jumlah: json['jumlah'] as int,
      pembeli: json['pembeli'] != null
          ? PembeliModel.fromJson(json['pembeli'])
          : null,
      merchandise: json['merchandise'] != null
          ? Merchandise.fromJson(json['merchandise'])
          : null,
    );
  }

  String get formattedTanggalPenukaran {
    return DateFormat('dd MMMM yyyy, HH:mm').format(tanggalPenukaran);
  }

  bool get isPegawaiKosong => idPegawai == null || idPegawai == 0;
}
