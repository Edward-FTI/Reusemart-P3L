// lib/data/models/barang/barang.dart
import 'package:intl/intl.dart';
import 'package:mobile/core/constants/variables.dart'; // <<<--- IMPORT BARU

class Barang {
  final int id;
  final int idPenitip;
  final int idKategori;
  final int? idPegawai;
  final int? idHunter;
  final DateTime tglPenitipan;
  final DateTime masaPenitipan;
  final int? penambahanDurasi;
  final DateTime? tglPengambilan;
  final String namaBarang;
  final double hargaBarang;
  final double beratBarang;
  final String deskripsi;
  final String? statusGaransi;
  final String statusBarang;
  final String? gambar;
  final String? gambarDua;

  Barang({
    required this.id,
    required this.idPenitip,
    required this.idKategori,
    this.idPegawai,
    this.idHunter,
    required this.tglPenitipan,
    required this.masaPenitipan,
    this.penambahanDurasi,
    this.tglPengambilan,
    required this.namaBarang,
    required this.hargaBarang,
    required this.beratBarang,
    required this.deskripsi,
    this.statusGaransi,
    required this.statusBarang,
    this.gambar,
    this.gambarDua,
  });

  factory Barang.fromJson(Map<String, dynamic> json) {
    return Barang(
      id: json['id'] as int,
      idPenitip: json['id_penitip'] as int,
      idKategori: json['id_kategori'] as int,
      idPegawai: json['id_pegawai'] as int?,
      idHunter: json['id_hunter'] as int?,
      tglPenitipan: DateTime.parse(json['tgl_penitipan'] as String),
      masaPenitipan: DateTime.parse(json['masa_penitipan'] as String),
      penambahanDurasi: json['penambahan_durasi'] as int?,
      tglPengambilan: json['tgl_pengambilan'] != null
          ? DateTime.parse(json['tgl_pengambilan'] as String)
          : null,
      namaBarang: json['nama_barang'] as String,
      hargaBarang: double.parse(json['harga_barang'].toString()),
      beratBarang: double.parse(json['berat_barang'].toString()),
      deskripsi: json['deskripsi'] as String,
      statusGaransi: json['status_garansi'] as String?,
      statusBarang: json['status_barang'] as String,
      gambar: json['gambar'] as String?,
      gambarDua: json['gambar_dua'] as String?,
    );
  }

  String get formattedHarga {
    final formatCurrency = NumberFormat.currency(locale: 'id_ID', symbol: 'Rp ', decimalDigits: 0);
    return formatCurrency.format(hargaBarang);
  }

  String get fullGambarUrl {
    if (gambar == null || gambar!.isEmpty) {
      return 'https://via.placeholder.com/150';
    }
    return '${Variables.baseUrl}/$gambar'; // Menggunakan Variables
  }
}