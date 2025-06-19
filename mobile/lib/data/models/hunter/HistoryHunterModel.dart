// To parse this JSON data, do
//
//     final historyHunterResponModel = historyHunterResponModelFromJson(jsonString);

import 'dart:convert';

HistoryHunterResponModel historyHunterResponModelFromJson(String str) => HistoryHunterResponModel.fromJson(json.decode(str));

String historyHunterResponModelToJson(HistoryHunterResponModel data) => json.encode(data.toJson());

class HistoryHunterResponModel {
    bool? status;
    String? message;
    List<Datum>? data;

    HistoryHunterResponModel({
        this.status,
        this.message,
        this.data,
    });

    factory HistoryHunterResponModel.fromJson(Map<String, dynamic> json) => HistoryHunterResponModel(
        status: json["status"],
        message: json["message"],
        data: json["data"] == null ? [] : List<Datum>.from(json["data"]!.map((x) => Datum.fromJson(x))),
    );

    Map<String, dynamic> toJson() => {
        "status": status,
        "message": message,
        "data": data == null ? [] : List<dynamic>.from(data!.map((x) => x.toJson())),
    };
}

class Datum {
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
    List<DetailTransaksi>? detailTransaksi;
    Pembeli? pembeli;

    Datum({
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
        this.detailTransaksi,
        this.pembeli,
    });

    factory Datum.fromJson(Map<String, dynamic> json) => Datum(
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
        detailTransaksi: json["detail_transaksi"] == null ? [] : List<DetailTransaksi>.from(json["detail_transaksi"]!.map((x) => DetailTransaksi.fromJson(x))),
        pembeli: json["pembeli"] == null ? null : Pembeli.fromJson(json["pembeli"]),
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
        "detail_transaksi": detailTransaksi == null ? [] : List<dynamic>.from(detailTransaksi!.map((x) => x.toJson())),
        "pembeli": pembeli?.toJson(),
    };
}

class DetailTransaksi {
    int? id;
    int? idTransaksiPenjualan;
    int? idBarang;
    int? hargaSaatTransaksi;
    DateTime? createdAt;
    DateTime? updatedAt;
    Barang? barang;

    DetailTransaksi({
        this.id,
        this.idTransaksiPenjualan,
        this.idBarang,
        this.hargaSaatTransaksi,
        this.createdAt,
        this.updatedAt,
        this.barang,
    });

    factory DetailTransaksi.fromJson(Map<String, dynamic> json) => DetailTransaksi(
        id: json["id"],
        idTransaksiPenjualan: json["id_transaksi_penjualan"],
        idBarang: json["id_barang"],
        hargaSaatTransaksi: json["harga_saat_transaksi"],
        createdAt: json["created_at"] == null ? null : DateTime.parse(json["created_at"]),
        updatedAt: json["updated_at"] == null ? null : DateTime.parse(json["updated_at"]),
        barang: json["barang"] == null ? null : Barang.fromJson(json["barang"]),
    );

    Map<String, dynamic> toJson() => {
        "id": id,
        "id_transaksi_penjualan": idTransaksiPenjualan,
        "id_barang": idBarang,
        "harga_saat_transaksi": hargaSaatTransaksi,
        "created_at": createdAt?.toIso8601String(),
        "updated_at": updatedAt?.toIso8601String(),
        "barang": barang?.toJson(),
    };
}

class Barang {
    int? id;
    int? idPenitip;
    int? idKategori;
    int? idPegawai;
    int? idHunter;
    DateTime? tglPenitipan;
    DateTime? masaPenitipan;
    int? penambahanDurasi;
    String? namaBarang;
    int? hargaBarang;
    int? beratBarang;
    String? deskripsi;
    DateTime? statusGaransi;
    String? statusBarang;
    dynamic tglPengambilan;
    String? gambar;
    String? gambarDua;
    DateTime? createdAt;
    DateTime? updatedAt;

    Barang({
        this.id,
        this.idPenitip,
        this.idKategori,
        this.idPegawai,
        this.idHunter,
        this.tglPenitipan,
        this.masaPenitipan,
        this.penambahanDurasi,
        this.namaBarang,
        this.hargaBarang,
        this.beratBarang,
        this.deskripsi,
        this.statusGaransi,
        this.statusBarang,
        this.tglPengambilan,
        this.gambar,
        this.gambarDua,
        this.createdAt,
        this.updatedAt,
    });

    factory Barang.fromJson(Map<String, dynamic> json) => Barang(
        id: json["id"],
        idPenitip: json["id_penitip"],
        idKategori: json["id_kategori"],
        idPegawai: json["id_pegawai"],
        idHunter: json["id_hunter"],
        tglPenitipan: json["tgl_penitipan"] == null ? null : DateTime.parse(json["tgl_penitipan"]),
        masaPenitipan: json["masa_penitipan"] == null ? null : DateTime.parse(json["masa_penitipan"]),
        penambahanDurasi: json["penambahan_durasi"],
        namaBarang: json["nama_barang"],
        hargaBarang: json["harga_barang"],
        beratBarang: json["berat_barang"],
        deskripsi: json["deskripsi"],
        statusGaransi: json["status_garansi"] == null ? null : DateTime.parse(json["status_garansi"]),
        statusBarang: json["status_barang"],
        tglPengambilan: json["tgl_pengambilan"],
        gambar: json["gambar"],
        gambarDua: json["gambar_dua"],
        createdAt: json["created_at"] == null ? null : DateTime.parse(json["created_at"]),
        updatedAt: json["updated_at"] == null ? null : DateTime.parse(json["updated_at"]),
    );

    Map<String, dynamic> toJson() => {
        "id": id,
        "id_penitip": idPenitip,
        "id_kategori": idKategori,
        "id_pegawai": idPegawai,
        "id_hunter": idHunter,
        "tgl_penitipan": tglPenitipan?.toIso8601String(),
        "masa_penitipan": masaPenitipan?.toIso8601String(),
        "penambahan_durasi": penambahanDurasi,
        "nama_barang": namaBarang,
        "harga_barang": hargaBarang,
        "berat_barang": beratBarang,
        "deskripsi": deskripsi,
        "status_garansi": "${statusGaransi!.year.toString().padLeft(4, '0')}-${statusGaransi!.month.toString().padLeft(2, '0')}-${statusGaransi!.day.toString().padLeft(2, '0')}",
        "status_barang": statusBarang,
        "tgl_pengambilan": tglPengambilan,
        "gambar": gambar,
        "gambar_dua": gambarDua,
        "created_at": createdAt?.toIso8601String(),
        "updated_at": updatedAt?.toIso8601String(),
    };
}

class Pembeli {
    int? id;
    String? namaPembeli;
    String? email;

    Pembeli({
        this.id,
        this.namaPembeli,
        this.email,
    });

    factory Pembeli.fromJson(Map<String, dynamic> json) => Pembeli(
        id: json["id"],
        namaPembeli: json["nama_pembeli"],
        email: json["email"],
    );

    Map<String, dynamic> toJson() => {
        "id": id,
        "nama_pembeli": namaPembeli,
        "email": email,
    };
}
