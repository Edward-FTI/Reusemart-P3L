// To parse this JSON data, do
//
//     final kurirSelesaiResponModel = kurirSelesaiResponModelFromJson(jsonString);

import 'dart:convert';

KurirSelesaiResponModel kurirSelesaiResponModelFromJson(String str) => KurirSelesaiResponModel.fromJson(json.decode(str));

String kurirSelesaiResponModelToJson(KurirSelesaiResponModel data) => json.encode(data.toJson());

class KurirSelesaiResponModel {
    String? message;
    Data? data;

    KurirSelesaiResponModel({
        this.message,
        this.data,
    });

    factory KurirSelesaiResponModel.fromJson(Map<String, dynamic> json) => KurirSelesaiResponModel(
        message: json["message"],
        data: json["data"] == null ? null : Data.fromJson(json["data"]),
    );

    Map<String, dynamic> toJson() => {
        "message": message,
        "data": data?.toJson(),
    };
}

class Data {
    Pengiriman? pengiriman;
    Penjualan? penjualan;

    Data({
        this.pengiriman,
        this.penjualan,
    });

    factory Data.fromJson(Map<String, dynamic> json) => Data(
        pengiriman: json["pengiriman"] == null ? null : Pengiriman.fromJson(json["pengiriman"]),
        penjualan: json["penjualan"] == null ? null : Penjualan.fromJson(json["penjualan"]),
    );

    Map<String, dynamic> toJson() => {
        "pengiriman": pengiriman?.toJson(),
        "penjualan": penjualan?.toJson(),
    };
}

class Pengiriman {
    int? id;
    int? idTransaksiPenjualan;
    int? idPegawai;
    dynamic tglPengiriman;
    String? statusPengiriman;
    int? biayaPengiriman;
    String? catatan;
    DateTime? createdAt;
    DateTime? updatedAt;

    Pengiriman({
        this.id,
        this.idTransaksiPenjualan,
        this.idPegawai,
        this.tglPengiriman,
        this.statusPengiriman,
        this.biayaPengiriman,
        this.catatan,
        this.createdAt,
        this.updatedAt,
    });

    factory Pengiriman.fromJson(Map<String, dynamic> json) => Pengiriman(
        id: json["id"],
        idTransaksiPenjualan: json["id_transaksi_penjualan"],
        idPegawai: json["id_pegawai"],
        tglPengiriman: json["tgl_pengiriman"],
        statusPengiriman: json["status_pengiriman"],
        biayaPengiriman: json["biaya_pengiriman"],
        catatan: json["catatan"],
        createdAt: json["created_at"] == null ? null : DateTime.parse(json["created_at"]),
        updatedAt: json["updated_at"] == null ? null : DateTime.parse(json["updated_at"]),
    );

    Map<String, dynamic> toJson() => {
        "id": id,
        "id_transaksi_penjualan": idTransaksiPenjualan,
        "id_pegawai": idPegawai,
        "tgl_pengiriman": tglPengiriman,
        "status_pengiriman": statusPengiriman,
        "biaya_pengiriman": biayaPengiriman,
        "catatan": catatan,
        "created_at": createdAt?.toIso8601String(),
        "updated_at": updatedAt?.toIso8601String(),
    };
}

class Penjualan {
    int? id;
    int? idPembeli;
    int? totalHargaPembelian;
    String? metodePengiriman;
    String? alamatPengiriman;
    int? ongkir;
    String? buktiPembayaran;
    String? statusPengiriman;
    String? statusPembelian;
    String? verifikasiPembayaran;
    DateTime? tglTransaksi;
    DateTime? createdAt;
    DateTime? updatedAt;

    Penjualan({
        this.id,
        this.idPembeli,
        this.totalHargaPembelian,
        this.metodePengiriman,
        this.alamatPengiriman,
        this.ongkir,
        this.buktiPembayaran,
        this.statusPengiriman,
        this.statusPembelian,
        this.verifikasiPembayaran,
        this.tglTransaksi,
        this.createdAt,
        this.updatedAt,
    });

    factory Penjualan.fromJson(Map<String, dynamic> json) => Penjualan(
        id: json["id"],
        idPembeli: json["id_pembeli"],
        totalHargaPembelian: json["total_harga_pembelian"],
        metodePengiriman: json["metode_pengiriman"],
        alamatPengiriman: json["alamat_pengiriman"],
        ongkir: json["ongkir"],
        buktiPembayaran: json["bukti_pembayaran"],
        statusPengiriman: json["status_pengiriman"],
        statusPembelian: json["status_pembelian"],
        verifikasiPembayaran: json["verifikasi_pembayaran"],
        tglTransaksi: json["tgl_transaksi"] == null ? null : DateTime.parse(json["tgl_transaksi"]),
        createdAt: json["created_at"] == null ? null : DateTime.parse(json["created_at"]),
        updatedAt: json["updated_at"] == null ? null : DateTime.parse(json["updated_at"]),
    );

    Map<String, dynamic> toJson() => {
        "id": id,
        "id_pembeli": idPembeli,
        "total_harga_pembelian": totalHargaPembelian,
        "metode_pengiriman": metodePengiriman,
        "alamat_pengiriman": alamatPengiriman,
        "ongkir": ongkir,
        "bukti_pembayaran": buktiPembayaran,
        "status_pengiriman": statusPengiriman,
        "status_pembelian": statusPembelian,
        "verifikasi_pembayaran": verifikasiPembayaran,
        "tgl_transaksi": "${tglTransaksi!.year.toString().padLeft(4, '0')}-${tglTransaksi!.month.toString().padLeft(2, '0')}-${tglTransaksi!.day.toString().padLeft(2, '0')}",
        "created_at": createdAt?.toIso8601String(),
        "updated_at": updatedAt?.toIso8601String(),
    };
}
